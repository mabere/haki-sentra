<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CrossrefService
{
    /**
     * Clean DOI string from URLs or prefixes.
     */
    public function cleanDoi(string $rawDoi): string
    {
        $doi = trim($rawDoi);
        $doi = preg_replace('#^https?://(dx\.)?doi\.org/#i', '', $doi);
        return trim($doi);
    }

    /**
     * Fetch publication metadata from Crossref API.
     */
    public function fetchByDoi(string $rawDoi): array
    {
        $doi = $this->cleanDoi($rawDoi);

        if (empty($doi)) {
            return [
                'success' => false,
                'message' => 'Format DOI tidak valid atau kosong.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'SIM-HKIPUB/1.0 (mailto:lppm@kampus.ac.id)',
            ])
            ->timeout(12)
            ->get("https://api.crossref.org/works/" . urlencode($doi));

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Metadata artikel dengan DOI tersebut tidak ditemukan di Crossref (HTTP ' . $response->status() . ').',
                ];
            }

            $data = $response->json('message') ?? [];

            // Parse title
            $title = '';
            if (!empty($data['title']) && is_array($data['title'])) {
                $title = trim($data['title'][0] ?? '');
            }

            // Parse journal name
            $journalName = '';
            if (!empty($data['container-title']) && is_array($data['container-title'])) {
                $journalName = trim($data['container-title'][0] ?? '');
            }

            // Parse publication year & date
            $year = null;
            $publishedDate = null;
            $dateParts = $data['published']['date-parts'][0]
                ?? $data['published-print']['date-parts'][0]
                ?? $data['published-online']['date-parts'][0]
                ?? null;

            if (!empty($dateParts)) {
                $year = $dateParts[0] ?? null;
                $month = str_pad((string)($dateParts[1] ?? 1), 2, '0', STR_PAD_LEFT);
                $day = str_pad((string)($dateParts[2] ?? 1), 2, '0', STR_PAD_LEFT);
                $publishedDate = $year ? "{$year}-{$month}-{$day}" : null;
            }

            // Parse ISSN
            $issn = null;
            if (!empty($data['ISSN']) && is_array($data['ISSN'])) {
                $issn = implode(', ', $data['ISSN']);
            }

            // Parse authors
            $authors = [];
            if (!empty($data['author']) && is_array($data['author'])) {
                $order = 1;
                foreach ($data['author'] as $author) {
                    $given = $author['given'] ?? '';
                    $family = $author['family'] ?? '';
                    $name = trim("{$given} {$family}");

                    if (empty($name) && !empty($author['name'])) {
                        $name = trim($author['name']);
                    }

                    $affiliation = '';
                    if (!empty($author['affiliation']) && is_array($author['affiliation'])) {
                        $affiliation = $author['affiliation'][0]['name'] ?? '';
                    }

                    if (!empty($name)) {
                        $authors[] = [
                            'name' => $name,
                            'order' => $order++,
                            'role' => $order === 2 ? 'first_author' : 'co_author',
                            'affiliation' => $affiliation,
                        ];
                    }
                }
            }

            return [
                'success' => true,
                'doi' => $doi,
                'title' => html_entity_decode($title, ENT_QUOTES | ENT_HTML5),
                'journal_name' => html_entity_decode($journalName, ENT_QUOTES | ENT_HTML5),
                'issn_isbn' => $issn,
                'volume' => $data['volume'] ?? null,
                'issue' => $data['issue'] ?? null,
                'pages' => $data['page'] ?? null,
                'publication_year' => $year,
                'publication_date' => $publishedDate,
                'url' => $data['resource']['primary']['URL'] ?? $data['URL'] ?? "https://doi.org/{$doi}",
                'publisher' => $data['publisher'] ?? null,
                'authors' => $authors,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghubungi layanan Crossref API: ' . $e->getMessage(),
            ];
        }
    }
}


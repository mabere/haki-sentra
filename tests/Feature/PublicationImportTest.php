<?php

namespace Tests\Feature;

use App\Models\Lecturer;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use App\Models\VerificationLog;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PublicationImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function test_preview_import_returns_unconfigured_error_when_no_sinta_or_scholar_id(): void
    {
        $lecturer = Lecturer::first();
        $lecturer->update([
            'sinta_id' => null,
            'google_scholar_id' => null,
        ]);

        $dosenUser = $lecturer->user;

        $response = $this->actingAs($dosenUser)
            ->postJson(route('dosen.publications.preview-import'));

        $response->assertOk();
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('belum diisi', $response->json('message'));
    }

    public function test_preview_import_fetches_and_deduplicates_articles(): void
    {
        $lecturer = Lecturer::first();
        $lecturer->update([
            'sinta_id' => '6012345',
            'google_scholar_id' => '7OCCVl8AAAAJ',
        ]);

        $dosenUser = $lecturer->user;

        // Mock SINTA and Scholar HTML responses with overlapping article
        $sintaHtml = '
        <div class="ar-list-item mb-5">
            <div class="ar-title">
                <a href="https://garuda.kemdikbud.go.id/1">Analisis Penerapan Media Digital untuk Pembelajaran Bahasa</a>
            </div>
            <div class="ar-meta">
                <a class="ar-pub">Jurnal Pendidikan Bahasa</a>
                <a class="ar-year">2024</a>
                <a class="ar-quartile">Accred : Sinta 2</a>
                <a class="ar-cited">DOI: 10.1234/jpb.v1i1.100</a>
            </div>
        </div>
        <div class="ar-list-item mb-5">
            <div class="ar-title">
                <a href="https://scopus.com/2">A Study on Critical Thinking in Argumentative Writing</a>
            </div>
            <div class="ar-meta">
                <a class="ar-pub">Journal of English Education</a>
                <a class="ar-year">2023</a>
                <a class="ar-quartile">Q1 as Journal</a>
                <a class="ar-cited">DOI: 10.5678/jee.2023.200</a>
            </div>
        </div>';

        $scholarHtml = '
        <table>
            <tr class="gsc_a_tr">
                <td class="gsc_a_t">
                    <a href="/citations?view_op=view_citation&citation_for_view=1" class="gsc_a_at">A Study on Critical Thinking in Argumentative Writing</a>
                    <div class="gs_gray">Ramli, Franscy</div>
                    <div class="gs_gray">Journal of English Education 10 (2), 45-60, 2023</div>
                </td>
                <td class="gsc_a_c"><a class="gsc_a_ac">5</a></td>
                <td class="gsc_a_y"><span class="gsc_a_h">2023</span></td>
            </tr>
            <tr class="gsc_a_tr">
                <td class="gsc_a_t">
                    <a href="/citations?view_op=view_citation&citation_for_view=2" class="gsc_a_at">Innovative Language Teaching Approaches</a>
                    <div class="gs_gray">Ahmad, Ramli</div>
                    <div class="gs_gray">International Language Review, 2022</div>
                </td>
                <td class="gsc_a_c"><a class="gsc_a_ac">2</a></td>
                <td class="gsc_a_y"><span class="gsc_a_h">2022</span></td>
            </tr>
        </table>';

        Http::fake([
            'https://sinta.kemdiktisaintek.go.id/*' => Http::response($sintaHtml, 200),
            'https://scholar.google.com/*' => Http::response($scholarHtml, 200),
        ]);

        $response = $this->actingAs($dosenUser)
            ->postJson(route('dosen.publications.preview-import'));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $articles = $response->json('articles');
        $this->assertNotEmpty($articles);

        $summary = $response->json('summary');
        $this->assertGreaterThan(0, $summary['total_found']);
        $this->assertGreaterThan(0, $summary['total_new']);

        // Verifikasi deduplikasi antara SINTA dan Scholar ("A Study on Critical Thinking in Argumentative Writing" digabung)
        $mergedItems = array_filter($articles, function ($item) {
            return in_array('sinta', $item['sources'] ?? []) && in_array('google_scholar', $item['sources'] ?? []);
        });

        $this->assertNotEmpty($mergedItems, 'Deduplikasi harus menggabungkan artikel yang muncul di SINTA dan Google Scholar.');
        $firstMerged = reset($mergedItems);
        $this->assertStringContainsString('Tergabung', $firstMerged['source_label']);
        $this->assertEquals('Q1', $firstMerged['quartile']);
    }

    public function test_preview_import_identifies_already_existing_publications(): void
    {
        $lecturer = Lecturer::first();
        $lecturer->update([
            'sinta_id' => '6012345',
            'google_scholar_id' => '7OCCVl8AAAAJ',
        ]);

        $dosenUser = $lecturer->user;

        $title = "Analisis Penerapan Media Digital untuk Pembelajaran Bahasa";
        $existing = Publication::create([
            'user_id' => $dosenUser->id,
            'title' => $title,
            'publication_type' => 'jurnal_nasional_sinta',
            'journal_name' => 'Jurnal Pendidikan Bahasa',
            'publication_year' => 2024,
            'verification_status' => 'approved',
        ]);

        $sintaHtml = '
        <div class="ar-list-item mb-5">
            <div class="ar-title">
                <a href="https://garuda.kemdikbud.go.id/1">Analisis Penerapan Media Digital untuk Pembelajaran Bahasa</a>
            </div>
            <div class="ar-meta">
                <a class="ar-pub">Jurnal Pendidikan Bahasa</a>
                <a class="ar-year">2024</a>
                <a class="ar-quartile">Accred : Sinta 2</a>
                <a class="ar-cited">DOI: 10.1234/jpb.v1i1.100</a>
            </div>
        </div>';

        Http::fake([
            'https://sinta.kemdiktisaintek.go.id/*' => Http::response($sintaHtml, 200),
            'https://scholar.google.com/*' => Http::response('', 200),
        ]);

        $response = $this->actingAs($dosenUser)
            ->postJson(route('dosen.publications.preview-import'));

        $response->assertOk();
        $articles = $response->json('articles');

        $matched = null;
        foreach ($articles as $art) {
            if ($art['is_already_imported'] && $art['existing_id'] === $existing->id) {
                $matched = $art;
                break;
            }
        }

        $this->assertNotNull($matched, 'Artikel yang sudah ada di database harus ditandai is_already_imported = true');
        $this->assertEquals('approved', $matched['existing_status']);
    }

    public function test_execute_import_persists_selected_articles_and_creates_authors(): void
    {
        $lecturer = Lecturer::first();
        $dosenUser = $lecturer->user;

        $articlesToImport = [
            [
                'title' => 'Studi Komparasi Kinerja Pembelajaran Bahasa Inggris',
                'journal_name' => 'Journal of Language and Literature',
                'publication_year' => 2024,
                'publication_type' => 'jurnal_internasional',
                'indexation' => 'scopus',
                'quartile' => 'Q1',
                'sinta_grade' => null,
                'doi' => '10.1109/JLL.2024.123456',
                'volume' => '10',
                'issue' => '2',
                'pages' => '100-115',
                'authors' => "{$lecturer->full_name}, Dr. Ahmad, Siti Rahayu",
                'source_label' => 'SINTA & Google Scholar (Tergabung)',
            ],
            [
                'title' => 'Pengembangan Model Pembelajaran Kontekstual',
                'journal_name' => 'Jurnal Rekayasa Pembelajaran Nasional',
                'publication_year' => 2024,
                'publication_type' => 'jurnal_nasional_sinta',
                'indexation' => 'sinta',
                'quartile' => null,
                'sinta_grade' => 'S2',
                'doi' => null,
                'volume' => '5',
                'issue' => '1',
                'pages' => '20-30',
                'authors' => $lecturer->full_name,
                'source_label' => 'SINTA',
            ]
        ];

        $response = $this->actingAs($dosenUser)
            ->postJson(route('dosen.publications.import'), [
                'articles' => $articlesToImport,
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'count' => 2,
        ]);

        // Verifikasi publikasi tersimpan dengan status pending
        $this->assertDatabaseHas('publications', [
            'user_id' => $dosenUser->id,
            'title' => 'Studi Komparasi Kinerja Pembelajaran Bahasa Inggris',
            'verification_status' => 'pending',
            'quartile' => 'Q1',
        ]);

        $pub = Publication::where('title', 'Studi Komparasi Kinerja Pembelajaran Bahasa Inggris')->first();
        $this->assertNotNull($pub);

        // Verifikasi penulis pertama adalah dosen yang bersangkutan
        $firstAuthor = $pub->authors()->where('author_order', 1)->first();
        $this->assertNotNull($firstAuthor);
        $this->assertEquals($lecturer->id, $firstAuthor->lecturer_id);
        $this->assertEquals('first_author', $firstAuthor->author_role);

        // Verifikasi co-authors dibuat
        $this->assertGreaterThan(1, $pub->authors()->count());

        // Verifikasi VerificationLog dibuat
        $this->assertDatabaseHas('verification_logs', [
            'verifiable_type' => Publication::class,
            'verifiable_id' => $pub->id,
            'status_before' => 'draft',
            'status_after' => 'pending',
        ]);
    }

    public function test_execute_import_rejects_empty_articles_array(): void
    {
        $lecturer = Lecturer::first();
        $dosenUser = $lecturer->user;

        $response = $this->actingAs($dosenUser)
            ->postJson(route('dosen.publications.import'), [
                'articles' => [],
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['articles']);
    }
}

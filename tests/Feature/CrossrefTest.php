<?php

namespace Tests\Feature;

use App\Services\CrossrefService;
use Tests\TestCase;

class CrossrefTest extends TestCase
{
    public function test_clean_doi(): void
    {
        $service = new CrossrefService();
        $this->assertEquals('10.1016/j.jbi.2023.104445', $service->cleanDoi('https://doi.org/10.1016/j.jbi.2023.104445'));
        $this->assertEquals('10.1016/j.jbi.2023.104445', $service->cleanDoi('http://dx.doi.org/10.1016/j.jbi.2023.104445'));
        $this->assertEquals('10.1016/j.jbi.2023.104445', $service->cleanDoi(' 10.1016/j.jbi.2023.104445 '));
    }

    public function test_fetch_crossref_api(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.crossref.org/*' => \Illuminate\Support\Facades\Http::response([
                'message' => [
                    'title' => ['Deep Learning Applications in Healthcare'],
                    'container-title' => ['Journal of Biomedical Informatics'],
                    'published' => ['date-parts' => [[2023, 5, 12]]],
                    'ISSN' => ['1532-0464'],
                    'volume' => '142',
                    'issue' => '1',
                    'page' => '104445',
                    'URL' => 'https://doi.org/10.1016/j.jbi.2023.104445',
                    'publisher' => 'Elsevier',
                    'author' => [
                        [
                            'given' => 'Ahmad',
                            'family' => 'Dahlan',
                            'affiliation' => [['name' => 'Universitas Lakidende Unaaha']],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = new CrossrefService();
        $result = $service->fetchByDoi('10.1016/j.jbi.2023.104445');

        $this->assertTrue($result['success']);
        $this->assertEquals('Deep Learning Applications in Healthcare', $result['title']);
        $this->assertEquals('Journal of Biomedical Informatics', $result['journal_name']);
        $this->assertIsArray($result['authors']);
        $this->assertEquals('Ahmad Dahlan', $result['authors'][0]['name']);
    }
}


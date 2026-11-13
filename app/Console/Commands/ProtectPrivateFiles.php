<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProtectPrivateFiles extends Command
{
    protected $signature = 'security:protect-private-files {--delete-public : Delete each public source only after its private copy is verified}';

    protected $description = 'Copy sensitive uploads from the public disk to private storage and optionally remove public copies';

    /** @var list<string> */
    private const SENSITIVE_DIRECTORIES = [
        'ipr/certificates',
        'ipr/descriptions',
        'publications/manuscripts',
        'publications/loa',
        'incentives/statements',
        'incentives/proofs',
        'certificates',
    ];

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk('private');
        $copied = 0;
        $alreadyPrivate = 0;
        $removed = 0;
        $errors = 0;

        foreach (self::SENSITIVE_DIRECTORIES as $directory) {
            foreach ($public->allFiles($directory) as $path) {
                try {
                    if (!$private->exists($path)) {
                        $stream = $public->readStream($path);

                        if (!is_resource($stream)) {
                            throw new \RuntimeException('Could not read source file.');
                        }

                        try {
                            if (!$private->writeStream($path, $stream)) {
                                throw new \RuntimeException('Could not write private copy.');
                            }
                        } finally {
                            fclose($stream);
                        }

                        $copied++;
                    } else {
                        $alreadyPrivate++;
                    }

                    $publicHash = $this->hashFile($public, $path);
                    $privateHash = $this->hashFile($private, $path);

                    if ($publicHash === null || $privateHash === null || !hash_equals($publicHash, $privateHash)) {
                        throw new \RuntimeException('Private copy verification failed; public source was retained.');
                    }

                    if ($this->option('delete-public')) {
                        if (!$public->delete($path)) {
                            throw new \RuntimeException('Verified private copy exists, but public source could not be deleted.');
                        }

                        $removed++;
                    }
                } catch (Throwable $exception) {
                    $errors++;
                    $this->components->error("{$path}: {$exception->getMessage()}");
                }
            }
        }

        $this->newLine();
        $this->line("Copied and verified: {$copied}");
        $this->line("Already present and verified: {$alreadyPrivate}");
        $this->line("Public copies removed: {$removed}");
        $this->line("Errors: {$errors}");

        if (!$this->option('delete-public')) {
            $this->warn('Public copies were retained. Run this command again with --delete-public after reviewing the verified copies.');
        }

        return $errors === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function hashFile(\Illuminate\Contracts\Filesystem\Filesystem $disk, string $path): ?string
    {
        $stream = $disk->readStream($path);

        if (!is_resource($stream)) {
            return null;
        }

        try {
            $context = hash_init('sha256');
            hash_update_stream($context, $stream);

            return hash_final($context);
        } finally {
            fclose($stream);
        }
    }
}

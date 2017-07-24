<?php

namespace Piesync\Partner\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FileSystem;
use Piesync\Partner\Credential;
use Piesync\Partner\OpenSSL;
use Piesync\Partner\Payload;
use Piesync\Partner\TokenGenerator;
use Carbon\Carbon;

class PartnerTokenGenerator extends Command
{

    protected $description = "Generate piesync partner API token";

    protected $signature = 'make:piesync-token {--pem-dir=} {--payload-file=}';

    protected $pemDir;

    protected $credential;

    protected $filesystem;

    protected $openSSL;

    protected $pemResource;

    protected $credentialPath;



    public function __construct(Filesystem $filesystem, Credential $credential, OpenSSL $openSSL)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->credential = $credential;
        $this->openSSL = $openSSL;
    }

    public function fire()
    {
        $this->credentialPath = $this->laravel->basePath('/private/piesync_secret.json');
        $this->pemDir = $this->option('pem-dir') ?: $this->laravel->basePath(). '/private';
        $this->pemDir = realpath($this->pemDir);


        if (!$this->checkCredential()) {
            $this->ensureDirectory($this->pemDir);
            $this->generatePem();
            $this->createPrivatePemFile();
            $this->createPublicPemFile();
            $this->compileCredential();
            $this->info('it works!');
        }

        $this->generateToken();
    }

    private function checkCredential()
    {
        if (!$this->filesystem->exists($this->credentialPath)) {
            return false;
        };

        $credential = $this->filesystem->get($this->credentialPath);
        $this->credential->unserialize($credential);
        return true;
    }


    private function ensureDirectory($directory)
    {

        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }
    }

    private function generatePem()
    {
        $this->pemResource = $this->openSSL->genRSA(2048);
    }

    private function createPrivatePemFile()
    {
        $pemFile = "{$this->pemDir}/piesync_partner_private.pem";
        $this->info("Create Pem File on {$pemFile}");

        $this->filesystem->put($pemFile, $this->openSSL->getPrivateKey($this->pemResource));
        $this->credential->privatePemFile = $pemFile;

    }

    private function createPublicPemFile()
    {
        $pemFile = "{$this->pemDir}/piesync_partner_public.pem";
        $this->info("Create public pem file on $pemFile");
        $this->filesystem->put($pemFile, $this->openSSL->getPublicKey($this->pemResource));
        $this->credential->publicPemFile = $pemFile;
    }

    private function compileCredential()
    {
        $this->credential->piesyncPublicPemFile = "{$this->pemDir}/piesync_public.pem";
        $this->ensureDirectory(dirname($this->credentialPath));
        $this->filesystem->put($this->credentialPath, $this->credential->serialize());
    }

    private function generateToken()
    {
        $payloadFile = $this->option('payload-file')
            ?: $this->laravel->basePath() . '/private/piesync_partner_payload.json';
        $payload = $this->laravel->make(Payload::class);
        $payload->unserialize($this->filesystem->get($payloadFile));
        $tokenGenerator = $this->laravel->make(TokenGenerator::class);

        $tokenGenerator->setPayload($payload)
            ->setExpiration(Carbon::now()->addMonth()->getTimestamp())
            ->setPrivateKeyFile($this->credential->privatePemFile)
            ->setPiesyncPublicKeyFile($this->credential->piesyncPublicPemFile)
            ->build();

    }

}

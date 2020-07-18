<?php

namespace MisMusic\JWTAuth\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class JWTSecretGenerate extends Command
{

    protected $signature = 'jwt-secret:generate
                            {--L|length= : 生成JWT secret的长度，默认为60}
                            {--S|show : 是否只显示生成的JWT secret}';

    protected $description = '生成一个JWT secret';

    public function handle()
    {
        $length = intval($this->option('length'));
        if (! $length) {
            $length = 60;
        }
        $key = Str::random($length);
        if ($this->option('show')) {
            return $this->info("jwt secret show [$key]");
        }
        if (file_exists($path = $this->envPath()) === false) {
            return $this->error('.env文件不存在');
        }
        if (Str::contains(file_get_contents($path), 'JWT_SECRET') === false) {
            file_put_contents($path, PHP_EOL . 'JWT_SECRET=' . $key . PHP_EOL, FILE_APPEND);  // 以追加的方式写入JWT_SECRET
        } else {
            file_put_contents($path, str_replace('JWT_SECRET=' . config('jwt.secret'),
                'JWT_SECRET=' . $key, file_get_contents($path)));
        }
        return $this->info("jwt secret [$key] generate successfully.");

    }

    private function envPath()
    {
        if (method_exists($this->getLaravel(), 'environmentFilePath')) {
            return $this->getLaravel()->environmentFilePath();
        }
        if (version_compare($this->getLaravel()->version(), '5.4.17', '<')) {
            return $this->getLaravel()->basePath() . DIRECTORY_SEPARATOR . '.env';
        }
        return $this->getLaravel()->basePath('.env');
    }

}
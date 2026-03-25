<?php
class RateLimiter {
    public static function check($ip) {
        $file = __DIR__ . "/../cache/" . md5($ip);
        $data = ['count' => 0, 'time' => time()];

        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);

            if (time() - $data['time'] < 3600) {
                if ($data['count'] >= 100) {
                    http_response_code(429);
                    die(json_encode(["error" => "Rate limit exceeded"]));
                }
                $data['count']++;
            } else {
                $data = ['count' => 1, 'time' => time()];
            }
        } else {
            $data['count'] = 1;
        }

        file_put_contents($file, json_encode($data));
    }
}

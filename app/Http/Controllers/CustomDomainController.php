<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class CustomDomainController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|string',
            'target' => 'required|url'
        ]);

        $domain = $request->input('domain');
        $target = $request->input('target');

        $safeName = str_replace(['.', '*'], ['-', 'wildcard'], $domain);
        $filePath = base_path("traefik/dynamic/{$safeName}.yml");

        $yaml = [
            'http' => [
                'routers' => [
                    "{$safeName}-router" => [
                        'rule' => "Host(`{$domain}`)",
                        'entryPoints' => ['web'],
                        'service' => "{$safeName}-service",
                        'tls' => [
                            'certResolver' => 'letsencrypt'
                        ],
                    ],
                ],
                'services' => [
                    "{$safeName}-service" => [
                        'loadBalancer' => [
                            'servers' => [
                                ['url' => $target]
                            ]
                        ]
                    ],
                ]
            ]
        ];

        // Cria o arquivo YAML para o Traefik
        File::put($filePath, Yaml::dump($yaml, 10, 2));

        return response()->json([
            'message' => "Domínio {$domain} configurado com sucesso.",
            'file' => $filePath
        ]);
    }
}

# SDK em PHP para o Autentique
SDK em php para assinatura digital de documentos utilizando o Autentique.

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a0ab6c3ea7ae4b03991d85eb0b8ec533)](https://www.codacy.com/app/marciodojr/autentique?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=marciodojr/autentique&amp;utm_campaign=Badge_Grade) [![Build Status](https://travis-ci.org/marciodojr/autentique.svg?branch=master)](https://travis-ci.org/marciodojr/autentique) [![StyleCI](https://github.styleci.io/repos/135076282/shield?branch=master)](https://github.styleci.io/repos/135076282) [![Coverage Status](https://coveralls.io/repos/github/marciodojr/autentique/badge.svg?branch=master)](https://coveralls.io/github/marciodojr/autentique?branch=master)


## Observação

Essa SDK foi construída devido a necessidade de integração de um projeto de terceiro com o [Autentique](https://www.autentique.com.br/). Foi publicada com a ideia de que possa ser útil para outras pessoas. Este repositório (e, consequentemente, seu dono) não possuem qualquer vínculo com o Autentique.

## Como instalar
```
composer require marciodojr/autentique
```

## Como usar

```php

use Mdojr\Autentique\Autentique;
use Mdojr\Autentique\Endpoint;
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => Endpoint::SANDBOX, // or Endpoint::PRODUCTION
    'headers' => [
        'X-Autntiq-Api' => $sandboxToken // respectively $productionToken
    ]
]);

$autentique = new Autentique($client);

// Manipulação de conta
$acc = $autentique->account();
// Manipulação de Documentos
$doc = $autentique->document();
// Manipulação de Assinaturas
$sign = $autentique->signature();
// Manipulação de Pastas
$folder = $autentique->folder();

```

### Conta
Informações de conta:
```php
$result = $acc->info();
```
### Documento

Listar todos os documentos que não estão em uma pasta:
```php
$page = 1;
$count = 60;
$s = ''; // opcional
$result = $doc->listNotInFolder($page, $count, $s);
```
Resgata informações sobre documento específico
```php
$uuid = '9ab23456325c40c2a5023051cf4bbf0e' // uuid do documento
$result = $doc->retrieve($uuid);
```
Lista apenas documentos recebidos que não estão em numa pasta
```php
$page = 1;
$count = 60;
$s = ''; // opcional
$doc->listReceivedNotInFolder($page, $count, $s);
```
Lista apenas documentos enviados
```php
$page = 1;
$count = 60;
$s = ''; // opcional
$doc->listOnlySent($page, $count, $s);
```
Criar um novo documento
```php
$nome = 'Contrato de aluguel';
$partes = [
    [
        'email' => 'umemail@gmail.com',
        'funcao' => 'assinar'
    ]
];
$arquivo = fopen('meu_arquivo.txt', 'r');
$rejeitavel = false;
$mensagem = 'Mensagem qualquer'; // opcional
$lembreteAssinatura = false; // opcional
$lembreteVencimento = true; // opcional
$frequencia = 'semanal'; // ou 'diario' opcional
$diasVencimento = 7; // opcional
$dataVencimento = '2018-09-10'; // opcional

$doc->create(
    $nome,
    $partes,
    $arquivo,
    $rejeitavel,
    $mensagem,
    $lembreteAssinatura,
    $lembreteVencimento,
    $frequencia,
    $diasVencimento,
    $dataVencimento
);
```
Reenviar email de assinatura para os signatários que não assinaram/rejeitaram
```php
$uuid = '9ab23456325c40c2a5023051cf4bbf0e'; // uuid do documento
$doc->resendNotSignedOrRejected($uuid);
```

Remover documento
```php
$uuid = '9ab23456325c40c2a5023051cf4bbf0e'; // uuid do documento
$result = $doc->deleteDocument($uuid);
```

### Assinatura
Resgatar informações sobre assinatura
```php
$token = 'bf0e456325c40c2a5023051cf4';
$result = $sign->retrieve($token);
```
Signatário aceitar o documento
```php
$token = 'bf0e456325c40c2a5023051cf4';
$result = $sign->accept($token);
```
Signatário rejeitar o documento (se po)
```php
$token = 'bf0e456325c40c2a5023051cf4';
$motivo = 'Não estou de acordo com os termos';
$result = $sign->reject($token, $motivo);
```

### Pasta
Listar pasta
```php
$s = ''; // opcional
$result = $folder->list($s);
```
Resgatar informações da pasta
```php
$uuid = '456325c40c2a5023051cf456325c40';
$result = $folder->retrieve($uuid);
```
Listar documentos da pasta
```php
$uuid = '456325c40c2a5023051cf456325c40';
$s = '' // opcional
$result = $folder->listDocuments($uuid, $s);
```
Criar pasta
```php
$nome = 'Pasta XYZ';
$result = $folder->create($nome);
```
Mover documentos para pasta
```php
$folderUuid = '456325c40c2a5023051cf456325c40';
$docSignUuids = [
    '456325c40c2a5023051cf56656325c40',
    '456325c40c2a502345451cf456325c40',
    '456325c40c2a502305ffsdf456325c40'
]; // token de assinaturas de documentos
$result = $folder->moveDocumentsTo($folderUuid, $docSignUuids);
```
Atualizar informações da pasta
```php
$uuid = '456325c40c2a5023051cf456325c40';
$nome = 'Outro nome';
$result = $folder->update($uuid, $nome);
```
Remover pasta (arquivos são movidos para "Todos")
```php
$uuid = '456325c40c2a5023051cf456325c40';
$result = $folder->deleteFolder($uuid);
```

### Para mais informações sobre parâmetros consulte a documentação da api

https://autentique.docs.apiary.io


## Como testar

Por padrão nenuma requisição é feita contra o ambiente de sandbox:
```
composer test
```

Caso queira testar contra o ambiente de sandbox:
```
export AUTENTIQUE_TOKEN=sandbox_token
composer test
```

## Licença
MIT

## TODO

- Testes para erros 4xx de pastas e assinaturas.
- Tratamento de erros de falha de conexão (5xx).

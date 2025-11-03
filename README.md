# Sinesys Authenticator (fork de 2FAuth)

![Docker build status](https://img.shields.io/github/actions/workflow/status/bubka/2fauth/ci-docker-test.yml?branch=master&style=flat-square)
![https://codecov.io/gh/Bubka/2FAuth](https://img.shields.io/codecov/c/github/Bubka/2FAuth?style=flat-square)
![https://github.com/Bubka/2FAuth/blob/master/LICENSE](https://img.shields.io/github/license/Bubka/2FAuth.svg?style=flat-square)

Aplicativo web para gerenciar contas de Autenticação em Duas Etapas (2FA) e gerar códigos de segurança.

Este projeto é um fork do [2FAuth](https://github.com/Bubka/2FAuth), com customizações e distribuição pela [Sinesys](https://github.com/SinesysTech/SinesysAuthenticator).

![screens](https://user-images.githubusercontent.com/858858/100485897-18c21400-3102-11eb-9c72-ea0b1b46ef2e.png)

[**2FAuth Demo**](https://demo.2fauth.app/)  
Credentials (login - password) : `demo@2fauth.app` - `demo`

## Propósito

Alternativa web e auto-hospedada a geradores de OTP como Google Authenticator, pensada para desktop e mobile.

Objetivos principais:

- Interface simples para gerenciar contas 2FA
- Armazenamento em banco próprio (backup/restauração fáceis)
- Uso confortável em desktop e mobile

## Principais recursos

* Gerencie contas 2FA e organize por grupos
* Leia QR Codes para adicionar contas rapidamente
* Adicione contas manualmente via formulário avançado
* Edite contas (inclusive importadas)
* Gere códigos TOTP/HOTP e Steam Guard

2FAuth is currently fully localized in English and French. See [Contributing](#contributing) if you want to help on adding more languages.

## Segurança

Inclui mecanismos para proteger seus dados 2FA.

### Aplicativo de usuário único

Um único usuário por instância; pensado para uso pessoal.

### Autenticação moderna

Suporte a WebAuthn (chaves de segurança) e opção de desativar login tradicional.

### Criptografia de dados

Dados sensíveis podem ser criptografados. Faça backup do `APP_KEY` quando ativar.

### Logout automático

Logout após inatividade (configurável).

### Conformidade RFC

Geração OTP conforme RFC 4226/6238 via [Spomky-Labs/OTPHP](https://github.com/Spomky-Labs/otphp).

## Requisitos

* [![Requires PHP8](https://img.shields.io/badge/php-^8.3-red.svg?style=flat-square)](https://secure.php.net/downloads.php)
* Requisitos do Laravel: [documentação](https://laravel.com/docs/installation#server-requirements)
* Qualquer banco suportado pelo Laravel (usamos SQLite por padrão)

## Instalação

* CapRover: ver `CAPROVER.md`
* Docker: imagem própria ou build via Dockerfile do repositório

## Atualização

Siga o processo de deploy para reconstruir a imagem; o app executa migrações automaticamente.

## Migração

Suporta importação de: 2FAuth (JSON), Google Auth (QR), Aegis Auth (JSON), 2FAS Auth (JSON).

* [Import guide](https://docs.2fauth.app/getting-started/usage/import/)

## Contribuindo

Você pode contribuir de várias formas:

* Reportando issues ou enviando PRs
* Sugerindo melhorias
* Ajudando com traduções

## Licença

[AGPL-3.0](https://www.gnu.org/licenses/agpl-3.0.html)

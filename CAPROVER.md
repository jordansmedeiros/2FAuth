# Deploy do 2FAuth no CapRover

Este guia descreve como fazer o deploy do 2FAuth no CapRover usando deploy automático via webhook do GitHub.

## Pré-requisitos

- CapRover instalado e configurado
- Repositório GitHub do 2FAuth
- Acesso ao painel do CapRover

## Resumo Rápido

**✅ SQLite funciona automaticamente!** Não é necessário configurar MySQL ou PostgreSQL. O sistema:
- Usa SQLite por padrão (já configurado no Dockerfile)
- Cria o banco de dados automaticamente no primeiro deploy
- Executa as migrações automaticamente
- Persiste os dados no volume configurado

**Configuração mínima necessária:**
1. Criar aplicação no CapRover
2. Configurar volume persistente em `/2fauth`
3. Definir apenas 4 variáveis de ambiente: `APP_KEY`, `APP_URL`, `APP_ENV`, `TRUSTED_PROXIES`
4. Configurar webhook do GitHub para deploy automático

Pronto! O restante funciona automaticamente.

## Configuração Inicial

### 1. Criar uma Nova Aplicação no CapRover

1. Acesse o painel do CapRover
2. Vá para "Apps" e clique em "One-Click Apps/Database" ou crie uma nova app manualmente
3. Clique em "Create New App" e dê um nome para sua aplicação (ex: `2fauth`)
4. **⚠️ IMPORTANTE:** Marque a opção **"Has Persistent Data"** (Possui dados persistentes)
   - Esta opção é essencial para que o banco de dados SQLite e os arquivos de storage sejam preservados
5. Certifique-se de que a aplicação está configurada para escutar na porta interna (CapRover detecta automaticamente a porta EXPOSE do Dockerfile)

### 2. Configurar Deploy Automático via GitHub

1. Na página da aplicação, vá para a aba "Deployment"
2. Selecione "GitHub" como método de deploy
3. Autorize o CapRover a acessar seu repositório GitHub (se ainda não autorizou)
4. Selecione o repositório e branch desejados (geralmente `master` ou `main`)
5. Ative o "Webhook Deployment"
6. Copie a URL do webhook fornecida

### 3. Configurar Webhook no GitHub

1. Acesse seu repositório no GitHub
2. Vá para **Settings** > **Webhooks**
3. Clique em **Add webhook**
4. Cole a URL do webhook copiada do CapRover
5. Selecione **application/json** como Content type
6. Selecione o evento: **Just the push event** (ou apenas os eventos que deseja)
7. Clique em **Add webhook**

Agora, sempre que você fizer push para o branch configurado, o CapRover irá automaticamente:
- Detectar o push
- Fazer pull do código
- Reconstruir a imagem Docker
- Fazer deploy da nova versão

## Variáveis de Ambiente

### Variáveis Obrigatórias

Configure estas variáveis no painel do CapRover em **App Configs** > **Environment Variables**:

```env
APP_KEY=<string-de-exatamente-32-caracteres>
APP_URL=https://2fauth.seudominio.com
APP_ENV=production
TRUSTED_PROXIES=*
```

**IMPORTANTE:** 
- `APP_KEY` deve ser uma string de exatamente 32 caracteres. Você pode gerar uma com:
  ```bash
  php artisan key:generate
  ```
  Ou usar qualquer string aleatória de 32 caracteres.

- `APP_URL` deve corresponder ao domínio configurado no CapRover para sua aplicação

- `TRUSTED_PROXIES` deve ser `*` para confiar em todos os proxies (necessário quando atrás do proxy reverso do CapRover)

### Banco de Dados SQLite (Configuração Automática)

**✅ SQLite é a configuração padrão e funciona automaticamente!**

O 2FAuth está configurado para usar SQLite por padrão. Você **NÃO precisa** configurar nenhuma variável de ambiente relacionada ao banco de dados. O sistema:

- ✅ Cria automaticamente o arquivo `database.sqlite` em `/2fauth/database.sqlite`
- ✅ Executa as migrações automaticamente na primeira inicialização
- ✅ Persiste os dados no volume montado em `/2fauth`

**Nenhuma configuração adicional é necessária para SQLite!** Apenas certifique-se de ter configurado o volume persistente conforme descrito na seção "Persistência de Dados".

### Variáveis Recomendadas para Produção

```env
APP_DEBUG=false
LOG_CHANNEL=daily
LOG_LEVEL=notice
CACHE_DRIVER=file
SESSION_DRIVER=file
```

**Nota:** As variáveis `DB_CONNECTION` e `DB_DATABASE` são opcionais se você quiser usar SQLite (que já é o padrão). Defina-as apenas se quiser usar MySQL ou PostgreSQL.

### Variáveis Opcionais

#### Configuração de Email

Se desejar enviar emails de notificação:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.exemplo.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@exemplo.com
MAIL_PASSWORD=sua-senha
MAIL_ENCRYPTION=tls
MAIL_FROM_NAME="2FAuth"
MAIL_FROM_ADDRESS=noreply@exemplo.com
MAIL_VERIFY_SSL_PEER=true
```

#### Configurações de Autenticação

```env
LOGIN_THROTTLE=5
AUTHENTICATION_LOG_RETENTION=365
```

#### WebAuthn

```env
WEBAUTHN_NAME=2FAuth
WEBAUTHN_ID=null
WEBAUTHN_USER_VERIFICATION=preferred
```

#### Outras Configurações

```env
SITE_OWNER=seu-email@exemplo.com
APP_TIMEZONE=America/Sao_Paulo
THROTTLE_API=60
IS_DEMO_APP=false
CONTENT_SECURITY_POLICY=true
```

## Persistência de Dados

O 2FAuth armazena dados no diretório `/2fauth` dentro do container:

- **Database SQLite**: `/2fauth/database.sqlite` (criado automaticamente)
- **Storage**: `/2fauth/storage` (logs, cache, arquivos temporários)

### Configurar Volume Persistente

**⚠️ IMPORTANTE:** Configure o volume **ANTES** do primeiro deploy para garantir que os dados sejam persistidos.

No CapRover, configure um volume para persistir os dados:

1. Na página da aplicação, vá para **Volumes** ou **Diretórios Persistentes**
2. Adicione um novo volume preenchendo os campos:
   
   **Caminho no App** (Path in App):
   ```
   /2fauth
   ```
   - Este é o caminho dentro do container onde os dados serão armazenados
   
   **Rótulo** (Label):
   ```
   2fauth-data
   ```
   - Este é apenas um nome identificador para você (pode ser qualquer nome descritivo)
   - Exemplos: `2fauth-data`, `dados-2fauth`, `persistent-storage`

3. Se necessário, configure opções adicionais:
   - **Is Dir**: ✓ (deve estar marcado)
   - **Define specific path on host**: Use apenas se precisar de um caminho específico no host

Isso garantirá que seus dados sejam preservados mesmo após atualizações ou reinicializações do container.

**Nota sobre SQLite:** O banco de dados SQLite será criado automaticamente no primeiro deploy dentro do volume persistente. Não é necessário criar manualmente o arquivo `database.sqlite`.

**Resumo dos campos:**
- **Caminho no App**: `/2fauth` (caminho fixo usado pelo 2FAuth)
- **Rótulo**: Qualquer nome descritivo (ex: `2fauth-data`)

## Configuração de Domínio

1. Na página da aplicação, vá para **HTTP Settings**
2. Configure seu domínio personalizado
3. Ative HTTPS (CapRover gerencia certificados SSL automaticamente via Let's Encrypt)

Certifique-se de que a variável `APP_URL` corresponda ao domínio configurado.

## Monitoramento e Logs

### Visualizar Logs

No painel do CapRover:
1. Vá para a página da aplicação
2. Clique na aba **Logs** para ver logs em tempo real

### Health Check

O 2FAuth expõe a porta 8000. O CapRover automaticamente verifica a saúde da aplicação através dessa porta.

## Troubleshooting

### Build Falha

- Verifique os logs de build no CapRover
- Certifique-se de que o Dockerfile está na raiz do projeto
- Verifique se o `captain-definition` está presente e correto

### Aplicação Não Inicia

- Verifique as variáveis de ambiente obrigatórias
- Verifique os logs da aplicação no CapRover
- Certifique-se de que `APP_KEY` tem exatamente 32 caracteres
- Verifique se `APP_URL` corresponde ao domínio configurado

### Assets Não Carregam

- Verifique se o build do frontend foi executado com sucesso
- Os assets devem estar em `/srv/public/build` no container
- Verifique se `ASSET_URL` está configurado corretamente (se usando CDN)

### Erro de Conexão com Banco de Dados

- Certifique-se de que o volume está montado corretamente em `/2fauth`
- Verifique permissões do arquivo de banco de dados (o container cria automaticamente com as permissões corretas)
- O arquivo SQLite será criado automaticamente em `/2fauth/database.sqlite` na primeira inicialização
- Se o erro persistir, verifique os logs da aplicação para ver a mensagem de erro exata

### WebAuthn Não Funciona

- Certifique-se de que `APP_URL` está configurado corretamente
- Verifique se está usando HTTPS (WebAuthn requer HTTPS em produção)
- Verifique se `TRUSTED_PROXIES` está configurado

## Atualizações

O CapRover automaticamente faz deploy de novas versões quando você faz push para o branch configurado. O processo:

1. GitHub envia webhook para CapRover
2. CapRover faz pull do código
3. CapRover reconstrói a imagem Docker (incluindo build do frontend)
4. CapRover para o container antigo e inicia o novo
5. A nova versão fica disponível

**Nota:** Durante o deploy, há um breve período de indisponibilidade. O CapRover tenta minimizar isso fazendo rolling updates quando possível.

## Backup

Para fazer backup dos dados:

1. No CapRover, vá para **Volumes**
2. Clique no volume `2fauth-data`
3. Faça download ou backup do conteúdo

Ou use SSH no servidor CapRover e copie o diretório do volume diretamente.

## Recursos Adicionais

- [Documentação do CapRover](https://caprover.com/docs/get-started.html)
- [Documentação do 2FAuth](https://docs.2fauth.app)
- [Repositório do 2FAuth](https://github.com/Bubka/2FAuth)


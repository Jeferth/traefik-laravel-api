# Laravel + Traefik + Domínios Personalizados (File Provider)

Este projeto demonstra como criar uma API em Laravel que gerencia domínios personalizados para uma aplicação via Traefik, utilizando o **File Provider** para gerar arquivos YAML dinamicamente e permitir que novos domínios sejam adicionados **sem reiniciar o Traefik**.

---

## 🔧 Stack utilizada

- Laravel 12
- PHP 8.2 (via Docker)
- Traefik v2.10 (como reverse proxy)
- Docker Compose
- File Provider do Traefik (para roteamento dinâmico)
- Let's Encrypt (opcional, não usado em ambiente local)

---

## 🔹 O que este projeto faz

- Permite cadastrar domínios personalizados para acessar a aplicação
- Gera arquivos `.yml` automaticamente com configurações de roteamento para o Traefik
- O Traefik detecta essas mudanças automaticamente e aplica as rotas

---

## 🎓 Como rodar o projeto localmente

### 1. Clone o repositório

```bash
git clone https://seurepositorio.com
cd nome-do-projeto
```

### 2. Instale as dependências do Laravel (opcional, se quiser rodar fora do container)

```bash
composer install
```

### 3. Suba os containers

```bash
docker-compose up --build
```

Isto subirá:
- Laravel em `http://localhost` (via Traefik)
- Traefik Dashboard em `http://localhost:8080`

### 4. Configure seu `/etc/hosts` (Linux/Mac) ou `C:\Windows\System32\drivers\etc\hosts`

Adicione a linha:

```
127.0.0.1 api.localhost
```

> Isso permite que o domínio `api.localhost` funcione corretamente.

### 5. Acesse a rota de teste

```bash
http://api.localhost/api/ping
```

Se tudo estiver certo, você verá:
```json
{
  "pong": true
}
```

---

## 🔎 Testar via Insomnia ou Postman

- Use: `http://api.localhost/api/ping`
- Se der erro, adicione manualmente o header:
  ```
Host: api.localhost
```
- Certifique-se de estar usando **HTTP**, não HTTPS (exceto se configurar um domínio válido com SSL)

---

## ✉️ Adicionando um novo domínio personalizado

Requisição:

**POST /api/custom-domains**

```json
{
  "domain": "cliente2.localhost",
  "target": "http://app:80"
}
```

O Laravel vai criar o arquivo YAML `traefik/dynamic/cliente2-localhost.yml`, e você poderá acessar:

```
http://cliente2.localhost/api/ping
```

Lembre-se de adicionar `cliente2.localhost` ao seu `hosts` local.

---

## 🌐 Como funciona o roteamento com Traefik

- Cada arquivo `.yml` criado em `traefik/dynamic/` é lido pelo Traefik
- O Traefik recarrega automaticamente sem reiniciar o container
- A API Laravel cria esses arquivos dinamicamente com base na requisição POST

---

## ✅ Troubleshooting

### Erro 404 no Insomnia?
- Verifique se está usando `http://api.localhost`
- Confirme se `api.localhost` está no seu `hosts`
- Use `curl -H "Host: api.localhost" http://localhost/api/ping` para testar manualmente

### Traefik não mostra a rota no dashboard?
- Verifique se o arquivo `.yml` foi criado corretamente
- Verifique permissões da pasta `traefik/dynamic`
- Rode `docker-compose logs -f traefik` para mensagens

---

## 🌟 Melhorias futuras

- Interface de gerenciamento dos domínios
- Deleção de arquivos YAML
- Suporte a certificados automáticos com domínios reais (Let's Encrypt DNS-01)

---

Feito com ❤️ usando Laravel + Traefik + Docker!


# Site L-Vision — reposicionamento

Novo site institucional da L-Vision Segurança Eletrônica, construído a partir do reposicionamento de marca
(tese "L-Vision não vende câmera, projeta sistema"). Site estático (HTML/CSS/JS puro, sem build step).

## Status

Páginas prontas:
- `index.html` — home (hero, sistemas, teaser de engenharia, projetos, empresa, diagnóstico)
- `engenharia.html` — portfólio de software (Sniper ERP, Escalas PM, L-Finanças, Transporte Escolar)
- `sistemas.html` — detalhamento dos 4 módulos de segurança física + FAQ
- `diagnostico.html` — formulário de contato qualificador, com envio real via `enviar-contato.php`
- `politica-de-privacidade.html`, `termos-de-servico.html` — páginas legais

Ainda não construídas como páginas próprias (hoje são seções dentro de `index.html`):
- `/projetos`, `/empresa` — podem ganhar página dedicada numa fase futura.

## Formulário de contato

`diagnostico.html` envia via `fetch()` para `enviar-contato.php`, que usa `mail()` nativo do PHP — **requer que o
host suporte PHP** (padrão em hospedagem compartilhada como a HostGator). Não testado localmente por falta de
interpretador PHP no ambiente de build; validar em produção antes de divulgar o formulário.

## Rodando localmente

Qualquer servidor estático funciona para as páginas HTML, por exemplo:

```bash
npx serve .
```

O formulário de contato (`enviar-contato.php`) só funciona num servidor com PHP.

## Estrutura

```
index.html
engenharia.html
sistemas.html
diagnostico.html
enviar-contato.php
politica-de-privacidade.html
termos-de-servico.html
assets/fonts/   — IBM Plex Sans, Plex Sans Condensed e Plex Mono (self-hosted, sem CDN externo)
```

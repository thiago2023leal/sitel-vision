# Site L-Vision — reposicionamento

Novo site institucional da L-Vision Segurança Eletrônica, construído a partir do reposicionamento de marca
(tese "L-Vision não vende câmera, projeta sistema"). Site estático (HTML/CSS/JS puro, sem build step).

## Status

Páginas prontas:
- `index.html` — home (hero, sistemas, teaser de engenharia, projetos, empresa, diagnóstico)
- `engenharia.html` — portfólio de software (Sniper ERP, Escalas PM, L-Finanças, Transporte Escolar)
- `sistemas.html` — detalhamento dos 4 módulos de segurança física + FAQ
- `diagnostico.html` — formulário de contato qualificador, com envio via FormSubmit.co
- `politica-de-privacidade.html`, `termos-de-servico.html` — páginas legais
- `pages/sobre.html`, `pages/rastreadores.html`, `pages/termos-de-servico.html` — páginas legadas
  reconstruídas no design novo (URLs antigas mantidas vivas)

Ainda não construídas como páginas próprias (hoje são seções dentro de `index.html`):
- `/projetos`, `/empresa` — podem ganhar página dedicada numa fase futura.

## Formulário de contato

`diagnostico.html` envia via `fetch()` direto para `https://formsubmit.co/ajax/thiago2023leal@gmail.com`.
Site 100% estático, sem backend próprio — o `mail()` nativo do PHP foi abandonado porque a hospedagem
(HostGator) não entregava de forma confiável (roteamento local do domínio e bloqueio/limite de envio direto).

Na primeira submissão, o FormSubmit manda um e-mail de confirmação para `thiago2023leal@gmail.com` — é
preciso clicar no link de ativação antes que os envios seguintes realmente cheguem.

## Rodando localmente

Qualquer servidor estático funciona para as páginas HTML, por exemplo:

```bash
npx serve .
```

## Estrutura

```
index.html
engenharia.html
sistemas.html
diagnostico.html
politica-de-privacidade.html
termos-de-servico.html
robots.txt
sitemap.xml
pages/sobre.html
pages/rastreadores.html
pages/termos-de-servico.html
assets/fonts/   — IBM Plex Sans, Plex Sans Condensed e Plex Mono (self-hosted, sem CDN externo)
```

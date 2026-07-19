# Site L-Vision — reposicionamento

Novo site institucional da L-Vision Segurança Eletrônica, construído a partir do reposicionamento de marca
(tese "L-Vision não vende câmera, projeta sistema"). Site estático (HTML/CSS/JS puro, sem build step).

## Status

Páginas prontas:
- `index.html` — home (hero, sistemas, engenharia/portfólio, projetos, empresa, diagnóstico)
- `engenharia.html` — portfólio de software (Sniper ERP, Escalas PM, L-Finanças, Transporte Escolar)

Ainda não construídas como páginas próprias (hoje são seções dentro de `index.html`):
- `/sistemas`, `/projetos`, `/empresa`, `/diagnostico` — próxima fase do roadmap.

## Rodando localmente

Qualquer servidor estático funciona, por exemplo:

```bash
npx serve .
```

## Estrutura

```
index.html
engenharia.html
assets/fonts/   — IBM Plex Sans, Plex Sans Condensed e Plex Mono (self-hosted, sem CDN externo)
```

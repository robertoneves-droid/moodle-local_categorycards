# Plugin Moodle: Cards de Categoria (local_categorycards)

Um plugin local para Moodle desenvolvido para substituir a lista padrão de categorias de cursos por cards visuais elegantes, responsivos e personalizáveis. Este recurso otimiza a navegação e a estética visual da página inicial do Moodle, permitindo que administradores e gerentes de categorias configurem imagens de capa, cores de fundo e cores de texto personalizadas para cada categoria.

## 🌟 Funcionalidades

* **Layout de Cards Visuais:** Transforma a lista de categorias padrão do Moodle em uma grade moderna de cards visuais.
* **Grade Responsiva:** Suporta layouts responsivos dinâmicos (Automático) ou grades de colunas fixas (3 ou 4 colunas).
* **Personalização por Categoria:** Configure cores de fundo, cores de texto e imagens de capa de forma independente para cada categoria.
* **Ícone de Fallback Moderno:** Caso nenhuma imagem de capa seja enviada, um ícone SVG de formatura limpo e moderno é exibido como padrão.
* **Suporte a Categorias Ocultas:** Suaviza e esmaece visualmente os cards de categorias ocultas para os administradores, enquanto mantém a ocultação completa para os estudantes.

## ⚙️ Requisitos do Sistema

* **Versão do Moodle:** 4.3 ou superior.
* **Versão do PHP:** PHP 8.1 ou superior (alinhado aos requisitos do Moodle 4.3+).
* **Banco de Dados:** Totalmente compatível com PostgreSQL e MySQL/MariaDB.

## 🚀 Instalação

1. Baixe os arquivos do plugin.
2. Coloque a pasta `categorycards` dentro do diretório `local/` do seu Moodle. O caminho final deve ser: `[diretorio_moodle]/local/categorycards/`.
3. Acesse o Moodle como Administrador.
4. Navegue até **Administração do site > Notificações** para iniciar o processo de instalação.
5. Siga as instruções em tela para atualizar o banco de dados.
6. Navegue até **Administração do site > Desenvolvimento > Limpar todos os caches** para garantir o carregamento do CSS e Javascript (AMD) atualizados.

## ⚙️ Configurações Globais

Navegue até **Administração do site > Plugins > Plugins locais > Cards de Categoria** para configurar:
* **Ativar Plugin:** Ativa ou desativa o layout em cards globalmente no site.
* **Colunas do Layout:** Escolha entre "Responsivo (Automático)", "3 Colunas" ou "4 Colunas" para telas desktop.

## 🛠️ Como Personalizar os Cards de Categorias

1. Navegue até a página da categoria de cursos que deseja personalizar.
2. No menu/bloco de configurações da categoria, clique em **Configurações Personalizadas do Card da Categoria** (Category Card Custom Settings).
3. Defina as cores de sua preferência usando o seletor e envie a imagem de capa desejada.
4. Salve as alterações.

## 👥 Licença
Distribuído sob a licença GNU GPL v3 ou posterior.

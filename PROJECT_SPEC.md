# 📁 Sistema de Gerenciamento de Arquivos - Especificação do Projeto

## 🎯 Objetivo
Sistema para gerenciar download de arquivos de software para clientes, com controle de acesso granular por pastas.

---

## 🏗️ Arquitetura do Sistema

### Dois Painéis Principais:
1. **Painel Admin (Master)** - Gerenciamento completo
2. **Painel Cliente** - Acesso e download de arquivos

---

## 👨‍💼 PAINEL ADMIN (MASTER)

### 📑 Estrutura - 3 Abas:

#### 1️⃣ ABA ARQUIVOS
**Layout:** Grid grande (gerenciador de arquivos visual)

**Hierarquia de Pastas (2 níveis):**
```
GRUPO (Pasta Principal)
├── Arquivos (visíveis para TODOS os usuários do grupo)
├── SUBPASTA 1 (requer usuário + senha)
│   └── Arquivos (visíveis APENAS para o usuário desta subpasta)
└── SUBPASTA 2 (requer usuário + senha)
    └── Arquivos (visíveis APENAS para o usuário desta subpasta)
```

**Funcionalidades:**

**GRUPOS:**
- ✅ Criar grupo (requer apenas: nome)
- ✅ Renomear grupo
- ✅ Editar grupo
- ✅ Deletar grupo (deleta TUDO: subpastas, usuários e arquivos)
- ✅ Entrar no grupo para visualizar conteúdo

**SUBPASTAS:**
- ✅ Criar subpasta dentro de um grupo
  - Campos obrigatórios:
    - Nome da subpasta
    - Usuário (único, específico para esta subpasta)
    - Senha
- ✅ Renomear subpasta
- ✅ Editar subpasta
- ✅ Deletar subpasta (deleta usuário associado automaticamente)
- ⚠️ Limite: Apenas 2 níveis (Grupo > Subpasta) - SEM subpastas aninhadas

**ARQUIVOS:**
- ✅ Upload de arquivos (drag-and-drop + botão)
- ✅ Upload no nível do grupo (raiz)
- ✅ Upload dentro de subpastas
- ✅ Mover arquivos entre pastas
- ✅ Duplicar arquivos ao mover
- ✅ Deletar arquivos

**Exemplo Prático:**
```
📁 Grupo: "GridCompany"
├── 📄 vpn-installer.exe (visível para Pedro E João)
├── 📁 Subpasta: "Sistema de Cadastro"
│   ├── 👤 Usuário: pedro
│   ├── 🔑 Senha: 1234
│   └── 📄 setup-cadastro.zip (visível APENAS para Pedro)
└── 📁 Subpasta: "Controle de Estoque"
    ├── 👤 Usuário: joao
    ├── 🔑 Senha: 3456
    └── 📄 setup-estoque.zip (visível APENAS para João)
```

#### 2️⃣ ABA USUÁRIOS
**Funcionalidades:**
- ✅ Listar todos os usuários criados
- ✅ Filtro por grupo
- ✅ Exibir informações:
  - Nome de usuário
  - Grupo que pertence
  - Subpasta que pertence
  - Data de criação
- ✅ Editar credenciais (usuário e senha)
- ❌ NÃO pode criar usuários independentes
- ❌ NÃO pode criar múltiplos usuários por subpasta
- ❌ NÃO pode deletar usuário diretamente (só deletando a subpasta)

**Regras:**
- Usuário é ÚNICO e EXCLUSIVO para uma subpasta
- Usuário é automaticamente criado ao criar subpasta
- Usuário é automaticamente deletado ao deletar subpasta
- Usuário NÃO pode ser reutilizado em outras subpastas

#### 3️⃣ ABA HISTÓRICO DE DOWNLOADS
**Funcionalidades:**
- ✅ Registrar cada download realizado
- ✅ Exibir informações:
  - Nome do arquivo baixado
  - Usuário que baixou
  - Grupo/Subpasta de origem
  - Data e hora do download
  - IP do usuário (opcional)
- ✅ Filtros:
  - Por usuário
  - Por grupo
  - Por data
  - Por arquivo
- ✅ Exportar relatório (CSV/PDF)

---

## 👥 PAINEL CLIENTE

### 🔐 Autenticação:
- Cliente faz login UMA VEZ com usuário e senha
- Permanece logado durante a sessão
- NÃO precisa digitar senha novamente para acessar pastas

### 📂 Visualização de Arquivos:

**O que o cliente VÊ:**
1. **Grupos** que contém subpastas dele
2. **Arquivos na raiz do grupo** (nível principal)
3. **Apenas SUA subpasta** dentro do grupo
4. **Arquivos dentro da sua subpasta**

**O que o cliente NÃO VÊ:**
- ❌ Subpastas de outros usuários
- ❌ Arquivos dentro de subpastas de outros usuários
- ❌ Grupos onde ele não tem nenhuma subpasta

**Exemplo (visão do Pedro):**
```
📁 GridCompany
├── 📄 vpn-installer.exe ✅ (pode ver e baixar)
└── 📁 Sistema de Cadastro ✅ (pode ver e baixar)
    └── 📄 setup-cadastro.zip ✅

📁 GridCompany (visão do João)
├── 📄 vpn-installer.exe ✅ (pode ver e baixar)
└── 📁 Controle de Estoque ✅ (pode ver e baixar)
    └── 📄 setup-estoque.zip ✅
```

### 🔽 Funcionalidades do Cliente:
- ✅ Visualizar grupos e suas pastas
- ✅ Visualizar arquivos disponíveis
- ✅ Baixar arquivos
- ✅ Ver informações do arquivo (nome, tamanho, data de upload)
- ❌ NÃO pode fazer upload
- ❌ NÃO pode deletar
- ❌ NÃO pode editar

---

## 🎨 DESIGN SYSTEM

### Paleta de Cores:
```css
/* Cor Primária */
--primary: #f2c700;

/* Backgrounds */
--bg-primary: #171717;
--bg-secondary: #1e1e1e;

/* Textos */
--text-muted: #7e7e7e;
--text-light: #ffffff;
```

### Tipografia:
- **Font Family:** Plus Jakarta Sans
- Importar via Google Fonts ou localmente

### Estilo Visual:
- ✨ **Moderno e Minimalista**
- 🎯 **Ícones bonitos** (sugestão: Heroicons, Lucide, Phosphor)
- 📱 **Responsivo**
- 🌙 **Dark theme** (background escuro)
- 💛 **Destaques em amarelo** (cor primária)

### Componentes UI:
- Cards com bordas suaves
- Hover states bem definidos
- Transições suaves
- Grid responsivo para gerenciador de arquivos
- Drag-and-drop visual com feedback
- Modal para confirmações
- Toast notifications para feedback de ações

---

## 🗄️ ESTRUTURA DE BANCO DE DADOS

### Tabelas Necessárias:

#### 1. `admins`
```sql
id, name, email, password, created_at, updated_at
```

#### 2. `grupos` (Pastas principais)
```sql
id, nome, created_at, updated_at
```

#### 3. `subpastas`
```sql
id, grupo_id (FK), nome, usuario, password, created_at, updated_at
```

#### 4. `arquivos`
```sql
id, nome, nome_original, caminho, tamanho, tipo_mime, 
grupo_id (FK - nullable), subpasta_id (FK - nullable),
created_at, updated_at
```
**Regra:** Se `subpasta_id` é NULL, arquivo está na raiz do grupo

#### 5. `downloads`
```sql
id, arquivo_id (FK), subpasta_id (FK), usuario, 
ip_address, downloaded_at, created_at
```

---

## 🔒 REGRAS DE NEGÓCIO

### Controle de Acesso:
1. **Admin** tem acesso total a tudo
2. **Cliente** só vê:
   - Grupos onde tem subpasta
   - Arquivos na raiz desses grupos
   - Sua própria subpasta
   - Arquivos dentro da sua subpasta

### Usuários:
1. Usuário é criado JUNTO com a subpasta
2. Usuário é ÚNICO por subpasta (não reutilizável)
3. Usuário é deletado AUTOMATICAMENTE ao deletar subpasta
4. NÃO pode ter dois usuários na mesma subpasta
5. NÃO pode ter o mesmo nome de usuário em subpastas diferentes

### Arquivos:
1. Arquivos na raiz do grupo = visíveis para TODOS os usuários daquele grupo
2. Arquivos na subpasta = visíveis APENAS para o usuário daquela subpasta
3. Ao deletar grupo = deleta TODOS os arquivos e subpastas
4. Ao deletar subpasta = deleta TODOS os arquivos dentro dela
5. Ao mover arquivo = pode duplicar ou mover definitivamente

### Downloads:
1. Todo download deve ser registrado no histórico
2. Registrar: usuário, arquivo, data/hora, IP
3. Cliente pode baixar o mesmo arquivo múltiplas vezes
4. Histórico NÃO pode ser deletado pelo cliente

---

## 🛠️ STACK TECNOLÓGICA

- **Backend:** Laravel 12
- **Frontend:** Blade Templates + TailwindCSS 4
- **UI Components:** Flowbite (componentes prontos)
- **JavaScript:** Alpine.js (para interatividade)
- **Upload:** FilePond (drag-and-drop)
- **Ícones:** Heroicons / Flowbite Icons
- **Database:** MySQL (XAMPP)
- **Storage:** Laravel Storage (local)
- **Font:** Plus Jakarta Sans (Google Fonts)

---

## 📋 FUNCIONALIDADES TÉCNICAS

### Upload de Arquivos:
- ✅ Drag and drop
- ✅ Múltiplos arquivos simultâneos
- ✅ Barra de progresso
- ✅ Validação de tipo/tamanho
- ✅ Preview para imagens
- ✅ Ícones por tipo de arquivo

### Segurança:
- ✅ Autenticação separada (admin/cliente)
- ✅ Middleware de autorização
- ✅ Validação de acesso a arquivos
- ✅ Hash de senhas (bcrypt)
- ✅ CSRF protection
- ✅ Sanitização de nomes de arquivo
- ✅ Limite de tamanho de upload

### Performance:
- ✅ Eager loading de relacionamentos
- ✅ Cache de consultas frequentes
- ✅ Paginação de listas
- ✅ Lazy loading de arquivos grandes

---

## 🚀 ROTAS DO SISTEMA

### Admin Routes (auth:admin):
```
/admin/login
/admin/dashboard
/admin/arquivos
/admin/arquivos/grupos/create
/admin/arquivos/grupos/{id}
/admin/arquivos/subpastas/create
/admin/arquivos/upload
/admin/usuarios
/admin/usuarios/{id}/edit
/admin/historico
```

### Cliente Routes (auth:cliente):
```
/login
/dashboard
/arquivos
/arquivos/grupos/{id}
/arquivos/download/{id}
```

---

## ✅ CRITÉRIOS DE ACEITE

### Admin deve poder:
- [x] Criar/editar/deletar grupos
- [x] Criar/editar/deletar subpastas
- [x] Fazer upload de arquivos
- [x] Mover/duplicar arquivos
- [x] Ver lista de usuários
- [x] Editar credenciais de usuários
- [x] Ver histórico completo de downloads

### Cliente deve poder:
- [x] Fazer login com usuário/senha
- [x] Ver apenas seus grupos e arquivos
- [x] Baixar arquivos permitidos
- [x] Ver arquivos da raiz do grupo
- [x] Ver arquivos da sua subpasta
- [x] NÃO ver subpastas de outros usuários

### Sistema deve:
- [x] Registrar todos os downloads
- [x] Validar permissões de acesso
- [x] Impedir acesso não autorizado
- [x] Ser responsivo e moderno
- [x] Ter interface intuitiva

---

## 📝 NOTAS IMPORTANTES

1. **Prioridade:** Segurança e controle de acesso
2. **UX:** Interface deve ser intuitiva e rápida
3. **Escalabilidade:** Pensar em múltiplos grupos e usuários
4. **Manutenção:** Código limpo e bem documentado
5. **Testes:** Validar todas as regras de negócio

---

**Versão:** 1.0  
**Data:** 21/01/2026  
**Status:** 📋 Especificação Completa - Pronto para Desenvolvimento

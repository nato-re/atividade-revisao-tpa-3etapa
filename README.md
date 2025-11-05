# 🎯 Projeto Laravel: Sistema de Gerenciamento de Tarefas

## 📋 DESCRIÇÃO COMPLETA DOS REQUISITOS

### 🎯 REQUISITO 1: DELETAR TAREFA

**📖 Descrição Detalhada:**
Implementar a funcionalidade de exclusão de tarefas existentes no sistema. O usuário deve poder remover tarefas de forma segura, com confirmação visual e feedback adequado após a operação.

**🎯 Objetivos de Aprendizado:**
- Trabalhar com rotas DELETE no Laravel
- Implementar Route Model Binding
- Entender operações de destruição no Eloquent
- Fornecer feedback ao usuário com mensagens flash

**🔍 Conceitos Envolvidos:**
- Método HTTP DELETE
- Eloquent `delete()`
- Mensagens de sessão (flash data)
- Redirecionamentos

---

### 🎯 REQUISITO 2: CRIAR TAREFA

**📖 Descrição Detalhada:**
Desenvolver um formulário completo para criação de novas tarefas, incluindo campos para título e descrição. O sistema deve processar o envio do formulário e armazenar os dados no banco.

**🎯 Objetivos de Aprendizado:**
- Criar formulários HTML no Blade
- Processar requisições POST
- Trabalhar com métodos store em controllers
- Implementar redirecionamentos após ações

**🔍 Conceitos Envolvidos:**
- Método HTTP POST
- Formulários HTML com CSRF
- Métodos create/store em controllers
- Redirecionamentos com parâmetros

---

### 🎯 REQUISITO 3: VALIDAR CRIAÇÃO DE TAREFA

**📖 Descrição Detalhada:**
Implementar validação de dados no backend para garantir a integridade das informações. O sistema deve validar campos obrigatórios e formatos antes de salvar no banco.

**🎯 Objetivos de Aprendizado:**
- Aplicar validação de dados no Laravel
- Trabalhar com mensagens de erro personalizadas
- Entender regras de validação comuns
- Manipular erros de validação nas views

**🔍 Conceitos Envolvidos:**
- Validação com `validate()`
- Regras: required, string, max
- Diretiva `@error` no Blade
- Mensagens de erro automáticas

---

### 🎯 REQUISITO 4: ASSOCIAR TAREFA CRIADA AO USUÁRIO LOGADO

**📖 Descrição Detalhada:**
Garantir que cada tarefa criada seja automaticamente vinculada ao usuário atualmente autenticado no sistema, estabelecendo a relação de propriedade.

**🎯 Objetivos de Aprendizado:**
- Trabalhar com autenticação no Laravel
- Implementar relacionamentos Eloquent
- Usar métodos de associação automática
- Entender o conceito de escopo de usuário

**🔍 Conceitos Envolvidos:**
- Relacionamentos `hasMany()` e `belongsTo()`
- Método `auth()->user()`
- Associação com `user()->tasks()->create()`
- Chaves estrangeiras (user_id)

---

### 🎯 REQUISITO 5: GET /{user}/tasks

**📖 Descrição Detalhada:**
Criar uma rota e funcionalidade para listar tarefas específicas de um usuário, permitindo visualizar todas as tarefas associadas a um ID de usuário particular.

**🎯 Objetivos de Aprendizado:**
- Implementar Route Model Binding com User
- Trabalhar com parâmetros de rota
- Filtrar dados por relacionamento
- Criar views específicas para dados de usuário

**🔍 Conceitos Envolvidos:**
- Route Model Binding
- Parâmetros dinâmicos em rotas
- Carregamento de relacionamentos
- Filtragem de dados por usuário

---

### 🎯 REQUISITO 6: VALIDAÇÃO DE DELETE APENAS DO USUÁRIO

**📖 Descrição Detalhada:**
Implementar sistema de autorização para garantir que usuários só possam excluir suas próprias tarefas, prevenindo acesso não autorizado a dados de outros usuários.

**🎯 Objetivos de Aprendizado:**
- Implementar autorização simples
- Trabalhar com condições de segurança
- Fornecer feedback de erro de autorização
- Entender princípios básicos de segurança

**🔍 Conceitos Envolvidos:**
- Verificação de propriedade
- Condicionais de autorização
- Mensagens de erro de permissão
- Princípio de menor privilégio

---

### 🎯 REQUISITO 7: REQUISIÇÃO PARA API EXTERNA

**📖 Descrição Detalhada:**
Integrar com API externa do Quotable para buscar citações inspiradoras, enriquecendo a experiência do usuário com conteúdo dinâmico externo.

**🎯 Objetivos de Aprendizado:**
- Fazer requisições HTTP para APIs externas
- Trabalhar com parâmetros de query
- Tratar respostas JSON
- Implementar tratamento de erros em APIs

**🔍 Conceitos Envolvidos:**
- HTTP Client do Laravel
- Parâmetros de query (tags=wisdom)
- Decodificação de JSON
- Tratamento de exceções

---

## 🚀 IMPLEMENTAÇÃO PASSO A PASSO

### 📁 ESTRUTURA INICIAL DO PROJETO

**Passo 0.1 - Verifique a estrutura atual:**
```bash
# Execute no terminal para ver sua estrutura
php artisan route:list
ls -la app/Models/
```

**Passo 0.2 - Crie os arquivos necessários:**
```bash
# Se não existirem, crie:
php artisan make:controller TaskController --resource
php artisan make:model Task -m
```

---

### 🔧 REQUISITO 1: DELETAR TAREFA

**Passo 1.1 - Configure a Rota DELETE:**
```php
// routes/web.php
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
```

**Passo 1.2 - Implemente o Método Destroy:**
```php
// app/Http/Controllers/TaskController.php
public function destroy(Task $task)
{
    // Exclui a tarefa do banco de dados
    $task->delete();
    
    // Redireciona de volta com mensagem de sucesso
    return redirect()->back()->with('success', 'Tarefa excluída com sucesso!');
}
```

**Passo 1.3 - Adicione o Botão de Excluir:**
```blade
{{-- Na sua view de listagem de tarefas --}}
@foreach($tasks as $task)
    <div class="task-item">
        <h3>{{ $task->title }}</h3>
        <p>{{ $task->description }}</p>
        
        {{-- Formulário para excluir --}}
        <form action="{{ route('tasks.destroy', $task) }}" method="POST" 
              onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                🗑️ Excluir
            </button>
        </form>
    </div>
@endforeach
```

**Passo 1.4 - Adicione Mensagens de Feedback:**
```blade
{{-- No topo da sua view --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
```

---

### 🔧 REQUISITO 2: CRIAR TAREFA

**Passo 2.1 - Configure as Rotas de Criação:**
```php
// routes/web.php
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
```

**Passo 2.2 - Implemente o Método Create:**
```php
// app/Http/Controllers/TaskController.php
public function create()
{
    // Retorna a view com formulário de criação
    return view('tasks.create');
}
```

**Passo 2.3 - Crie a View do Formulário:**
```blade
{{-- resources/views/tasks/create.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Criar Nova Tarefa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>➕ Criar Nova Tarefa</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Título da Tarefa *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Digite o título da tarefa" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4"
                                          placeholder="Descreva detalhes da tarefa (opcional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary me-md-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Criar Tarefa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

---

### 🔧 REQUISITO 3: VALIDAR CRIAÇÃO DE TAREFA

**Passo 3.1 - Implemente o Método Store com Validação:**
```php
// app/Http/Controllers/TaskController.php
public function store(Request $request)
{
    // Valida os dados do formulário
    $validatedData = $request->validate([
        'title' => [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) {
                if (strlen(trim($value)) < 3) {
                    $fail('O título deve ter pelo menos 3 caracteres.');
                }
            }
        ],
        'description' => 'nullable|string|max:1000',
    ], [
        'title.required' => 'O título da tarefa é obrigatório.',
        'title.max' => 'O título não pode ter mais de 255 caracteres.',
        'description.max' => 'A descrição não pode ter mais de 1000 caracteres.'
    ]);

    // Aqui vamos adicionar a associação com usuário depois
    dd($validatedData); // Para testar a validação
}
```

**Passo 3.2 - Teste a Validação:**
- Tente enviar formulário sem título
- Tente enviar título com menos de 3 caracteres
- Verifique as mensagens de erro personalizadas

---

### 🔧 REQUISITO 4: ASSOCIAR TAREFA AO USUÁRIO LOGADO

**Passo 4.1 - Configure os Relacionamentos:**
```php
// app/Models/User.php
class User extends Authenticatable
{
    // Relacionamento: Um usuário tem muitas tarefas
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

// app/Models/Task.php
class Task extends Model
{
    // Relacionamento: Uma tarefa pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

**Passo 4.2 - Atualize a Migration da Tarefa:**
```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_tasks_table.php
public function up()
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}
```

**Passo 4.3 - Atualize o Método Store:**
```php
// app/Http/Controllers/TaskController.php
public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255|min:3',
        'description' => 'nullable|string|max:1000',
    ]);

    // 🔐 ASSOCIA AUTOMATICAMENTE AO USUÁRIO LOGADO
    $task = $request->user()->tasks()->create($validatedData);

    return redirect()->route('tasks.index')
                     ->with('success', "Tarefa '{$task->title}' criada com sucesso!");
}
```

---

### 🔧 REQUISITO 5: GET /{user}/tasks

**Passo 5.1 - Configure a Rota:**
```php
// routes/web.php
Route::get('/users/{user}/tasks', [TaskController::class, 'userTasks'])->name('users.tasks');
```

**Passo 5.2 - Implemente o Método userTasks:**
```php
// app/Http/Controllers/TaskController.php
public function userTasks(User $user)
{
    // Carrega as tarefas do usuário específico
    $tasks = $user->tasks()->latest()->get();
    
    return view('tasks.user-index', [
        'tasks' => $tasks,
        'user' => $user
    ]);
}
```

**Passo 5.3 - Crie a View Específica:**
```blade
{{-- resources/views/tasks/user-index.blade.php --}}
@extends('layouts.app') {{-- Se você tiver um layout --}}

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📋 Tarefas de {{ $user->name }}</h1>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                    ← Voltar para todas as tarefas
                </a>
            </div>
            
            @if($tasks->count() > 0)
                <div class="row">
                    @foreach($tasks as $task)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $task->title }}</h5>
                                    <p class="card-text text-muted">
                                        {{ Str::limit($task->description, 100) }}
                                    </p>
                                    <small class="text-muted">
                                        Criada em: {{ $task->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center">
                    <h5>📭 Nenhuma tarefa encontrada</h5>
                    <p class="mb-0">{{ $user->name }} ainda não criou nenhuma tarefa.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
```

---

### 🔧 REQUISITO 6: VALIDAÇÃO DE DELETE APENAS DO USUÁRIO

**Passo 6.1 - Atualize o Método Destroy:**
```php
// app/Http/Controllers/TaskController.php
public function destroy(Task $task)
{
    // 🔐 VERIFICA SE O USUÁRIO É O DONO DA TAREFA
    if (auth()->id() !== $task->user_id) {
        return redirect()->back()
                         ->with('error', 'Você não tem permissão para excluir esta tarefa!');
    }

    $taskTitle = $task->title;
    $task->delete();

    return redirect()->back()
                     ->with('success', "Tarefa '{$taskTitle}' excluída com sucesso!");
}
```

**Passo 6.2 - Atualize o Botão de Excluir para Mostrar Apenas para o Dono:**
```blade
{{-- Na listagem de tarefas --}}
@foreach($tasks as $task)
    <div class="task-item">
        <h3>{{ $task->title }}</h3>
        <p>{{ $task->description }}</p>
        
        {{-- Mostra botão de excluir apenas para o dono --}}
        @if(auth()->id() === $task->user_id)
            <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">
                    🗑️ Excluir
                </button>
            </form>
        @else
            <small class="text-muted">Tarefa de outro usuário</small>
        @endif
    </div>
@endforeach
```

**Passo 6.3 - Adicione Mensagem de Erro:**
```blade
{{-- No topo da view --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        ⚠️ {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
```

---

### 🔧 REQUISITO 7: REQUISIÇÃO PARA API EXTERNA

**Passo 7.1 - Atualize o Método Create:**
```php
// app/Http/Controllers/TaskController.php
use Illuminate\Support\Facades\Http;

public function create()
{
    $quote = null;
    
    try {
        // 🌟 FAZ REQUISIÇÃO PARA API EXTERNA
        $response = Http::timeout(10)->get('https://api.quotable.io/random', [
            'tags' => 'wisdom', // Parâmetro de query
        ]);
        
        if ($response->successful()) {
            $quoteData = $response->json();
            $quote = [
                'content' => $quoteData['content'],
                'author' => $quoteData['author'],
            ];
        }
    } catch (\Exception $e) {
        // Em caso de erro, não quebra a aplicação
        \Log::error('Erro ao buscar citação: ' . $e->getMessage());
        $quote = [
            'content' => 'A persistência é o caminho do êxito.',
            'author' => 'Charles Chaplin'
        ];
    }
    
    return view('tasks.create', compact('quote'));
}
```

**Passo 7.2 - Atualize a View para Mostrar a Citação:**
```blade
{{-- No topo do formulário em create.blade.php --}}
@if($quote)
    <div class="alert alert-light border mb-4">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <span class="fs-2">💡</span>
            </div>
            <div class="flex-grow-1 ms-3">
                <blockquote class="blockquote mb-0">
                    <p class="fst-italic">"{{ $quote['content'] }}"</p>
                    <footer class="blockquote-footer mt-2">
                        <cite title="Source Title">{{ $quote['author'] }}</cite>
                    </footer>
                </blockquote>
            </div>
        </div>
    </div>
@endif
```

---

## 🧪 TESTES MANUAIS - CHECKLIST

### ✅ Lista de Verificação Completa:

```bash
# Teste cada funcionalidade nesta ordem:

[ ] 1. DELETE Funcionalidade:
    [ ] Acesse a listagem de tarefas
    [ ] Clique em "Excluir" em uma tarefa sua
    [ ] Confirme a exclusão
    [ ] Verifique mensagem de sucesso
    [ ] Tarefa some da listagem

[ ] 2. CREATE Funcionalidade:
    [ ] Acesse /tasks/create
    [ ] Preencha o formulário
    [ ] Clique em "Criar Tarefa"
    [ ] Verifique redirecionamento
    [ ] Nova tarefa aparece na listagem

[ ] 3. VALIDAÇÃO Funcionalidade:
    [ ] Tente criar tarefa sem título
    [ ] Verifique mensagem de erro
    [ ] Tente título com 2 caracteres
    [ ] Verifique mensagem de erro
    [ ] Tente criar com título válido

[ ] 4. ASSOCIAÇÃO Funcionalidade:
    [ ] Crie uma tarefa logado
    [ ] Verifique no banco: user_id está correto
    [ ] Faça logout e login com outro usuário
    [ ] Verifique que não vê tarefas do primeiro

[ ] 5. ROTA USUÁRIO Funcionalidade:
    [ ] Acesse /users/1/tasks
    [ ] Veja apenas tarefas do usuário 1
    [ ] Acesse /users/2/tasks
    [ ] Veja apenas tarefas do usuário 2

[ ] 6. SEGURANÇA Funcionalidade:
    [ ] Tente excluir tarefa de outro usuário
    [ ] Verifique mensagem de erro
    [ ] Botão de excluir não aparece em tarefas alheias

[ ] 7. API Funcionalidade:
    [ ] Acesse /tasks/create
    [ ] Veja citação inspiradora
    [ ] Recarregue a página
    [ ] Citação muda (ou é a mesma se em cache)
```

---

## 🎯 DICAS FINAIS DE IMPLEMENTAÇÃO

### 🕒 Estratégia de Desenvolvimento:
1. **Implemente na ordem** dos requisitos
2. **Teste cada requisito** antes do próximo
3. **Use migrações** quando necessário: `php artisan migrate:fresh`
4. **Teste com dados reais** - crie vários usuários e tarefas

### 🔧 Comandos Úteis:
```bash
# Ver rotas criadas
php artisan route:list

# Recriar banco de dados
php artisan migrate:fresh --seed

# Testar manualmente
php artisan serve

# Ver logs em tempo real
tail -f storage/logs/laravel.log
```

### 🎊 Parabéns!
Cada requisito implementado é uma habilidade real de programação que você está desenvolvendo. Continue com calma, teste cada passo e celebre cada conquista! 🚀
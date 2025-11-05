<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_criar_uma_tarefa(): void
    {
        // Arrange: Criar usuário e simular autenticação
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'title' => 'Minha primeira tarefa',
            'description' => 'Descrição da minha tarefa'
        ];

        // Act: Fazer requisição POST para criar tarefa
        $response = $this->post(route('tasks.store'), $taskData);

        // Assert: Verificar se foi criada no banco e redirecionamento
        $this->assertDatabaseHas('tasks', [
            'title' => 'Minha primeira tarefa',
        ]);

        $response->assertRedirect(route('tasks.index'));
    }

    public function test_validacao_impede_criacao_de_tarefa_sem_titulo(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('tasks.store'), [
            'title' => '', // Título vazio
            'description' => 'Descrição válida'
        ]);

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_validacao_impede_titulo_com_menos_de_3_caracteres(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('tasks.store'), [
            'title' => 'ab', // Apenas 2 caracteres
            'description' => 'Descrição válida'
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_tarefa_eh_automaticamente_associada_ao_usuario_logado(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('tasks.store'), [
            'title' => 'Tarefa com dono',
            'description' => 'Descrição'
        ]);

        // Verificar se a tarefa tem o user_id correto
        $task = Task::first();
        $this->assertEquals($user->id, $task->user_id);
        $this->assertEquals($user->id, auth()->id());
    }

    public function test_usuario_pode_criar_uma_tarefa(): void
    {
        // Arrange: Criar usuário e simular autenticação
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'title' => 'Minha primeira tarefa',
            'description' => 'Descrição da minha tarefa'
        ];

        // Act: Fazer requisição POST para criar tarefa
        $response = $this->post(route('tasks.store'), $taskData);

        // Assert: Verificar se foi criada no banco e redirecionamento
        $this->assertDatabaseHas('tasks', [
            'title' => 'Minha primeira tarefa',
            'user_id' => $user->id
        ]);

        $response->assertRedirect(route('tasks.index'));
    }


    public function test_usuario_pode_excluir_suas_proprias_tarefas(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('tasks.destroy', $task));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $response->assertRedirect();
    }

    public function test_usuario_nao_pode_excluir_tarefas_de_outro_usuario(): void
    {
        // Criar dois usuários
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Tarefa pertence ao user2
        $task = Task::factory()->create(['user_id' => $user2->id]);

        // user1 tenta excluir tarefa do user2
        $this->actingAs($user1);
        $response = $this->delete(route('tasks.destroy', $task));

        // Deve manter a tarefa no banco e retornar erro
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_rota_user_tasks_retorna_apenas_tarefas_do_usuario_especifico(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Criar tarefas para cada usuário
        $task1 = Task::factory()->create(['user_id' => $user1->id, 'title' => 'Tarefa User1']);
        $task2 = Task::factory()->create(['user_id' => $user2->id, 'title' => 'Tarefa User2']);

        $this->actingAs($user1);

        // Acessar rota do user1
        $response = $this->get("/users/{$user1->id}/tasks");

        $response->assertStatus(200);
        $response->assertSee('Tarefa User1');
        $response->assertDontSee('Tarefa User2');
    }

    public function test_rota_user_tasks_retorna_lista_vazia_para_usuario_sem_tarefas(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get("/users/{$user->id}/tasks");

        $response->assertStatus(200);
        $response->assertSee('Nenhuma tarefa encontrada');
    }

    public function test_api_externa_eh_chamada_na_pagina_de_criacao(): void
    {
        // Mock da resposta da API
        Http::fake([
            'api.quotable.io/random*' => Http::response([
                'content' => 'A sabedoria começa na reflexão.',
                'author' => 'Sócrates'
            ], 200)
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('tasks.create'));

        $response->assertStatus(200);
        
        // Verificar se a API foi chamada com os parâmetros corretos
        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.quotable.io/random' &&
                   $request['tags'] === 'wisdom';
        });
    }

    public function test_sistema_nao_quebra_quando_api_externa_falha(): void
    {
        // Simular falha na API
        Http::fake([
            'api.quotable.io/random*' => Http::response([], 500)
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('tasks.create'));

        // A página deve carregar normalmente mesmo com API falhando
        $response->assertStatus(200);
        $response->assertSee('Criar Nova Tarefa');
    }

    public function test_botao_excluir_nao_aparece_para_tarefas_de_outro_usuario(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $taskUser1 = Task::factory()->create(['user_id' => $user1->id]);
        $taskUser2 = Task::factory()->create(['user_id' => $user2->id]);

        $this->actingAs($user1);

        $response = $this->get(route('tasks.index'));

        // Deve ver o botão de excluir apenas para suas próprias tarefas
        $response->assertSee(route('tasks.destroy', $taskUser1));
        $response->assertDontSee(route('tasks.destroy', $taskUser2));
    }

    public function test_usuario_pode_acessar_formulario_de_criacao(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('tasks.create'));

        $response->assertStatus(200);
        $response->assertSee('Criar Nova Tarefa');
        $response->assertSee('Título da Tarefa');
        $response->assertSee('Descrição');
    }

    public function test_descricao_eh_opcional_na_criacao_da_tarefa(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('tasks.store'), [
            'title' => 'Tarefa sem descrição',
            'description' => '' // Descrição vazia
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'title' => 'Tarefa sem descrição',
            'description' => null
        ]);
    }
}
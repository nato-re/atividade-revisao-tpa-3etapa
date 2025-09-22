
# Projeto MusicLaravelCRUD — N:M (Músicas e Artistas)

Este projeto é uma extensão do CRUD de músicas e álbuns em Laravel, agora incluindo o relacionamento N:M (muitos para muitos) entre músicas e artistas. O objetivo é garantir que cada música possa ter vários artistas e cada artista possa estar em várias músicas, utilizando boas práticas de modelagem, migrations, Eloquent.

## Requisitos

### 1. Migration e Model de Artista
- Crie uma migration para a tabela `artists` contendo as colunas: `id`, `name`, `bio`, `created_at`, `updated_at`.
- Crie a model `Artist` correspondente.

### 2. Migration da tabela pivô artist_music
- Crie uma migration para a tabela pivô `artist_music` contendo as colunas: `artist_id`, `music_id`.
- Implemente as constraints de chave estrangeira para ambas as colunas, referenciando `artists` e `musics`.

### 3. Relacionamento N:M entre Music e Artist
- Implemente o relacionamento nas models:
	- `Artist` deve ter o método `musics()` (belongsToMany).
	- `Music` deve ter o método `artists()` (belongsToMany).

### 4. Cadastro e exibição de artistas em músicas
- Permita associar múltiplos artistas ao cadastrar ou editar uma música.
- Na listagem de músicas, exiba todos os artistas relacionados a cada música.



## Como executar os testes

Execute os testes automatizados para garantir que sua implementação está correta:

```bash
php artisan test --filter=MusicArtistTest
```

Todos os testes devem passar para que sua solução seja considerada válida.

## Dicas
- Siga o padrão RESTful para rotas e controllers.
- Consulte os testes em `tests/Feature/MusicArtistTest.php` para entender o comportamento esperado.
- Use migrations para garantir a estrutura correta do banco de dados.
- Implemente os relacionamentos Eloquent conforme a documentação oficial do Laravel.

Bons estudos e boa implementação!


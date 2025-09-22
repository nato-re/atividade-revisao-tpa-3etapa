
# Projeto MusicLaravelCRUD — N:M (Músicas e Artistas)

Este projeto é uma extensão do CRUD de músicas e álbuns em Laravel, agora incluindo o relacionamento N:M (muitos para muitos) entre músicas e artistas. O objetivo é garantir que cada música possa ter vários artistas e cada artista possa estar em várias músicas, utilizando boas práticas de modelagem, migrations, Eloquent e testes automatizados.

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
- Utilize factories para criar instâncias de `Artist` e `Music` nos testes e seeders.
- Siga o padrão RESTful para rotas e controllers.
- Consulte os testes em `tests/Feature/MusicArtistTest.php` para entender o comportamento esperado.
- Use migrations para garantir a estrutura correta do banco de dados.
- Implemente os relacionamentos Eloquent conforme a documentação oficial do Laravel.

Bons estudos e boa implementação!

## Objetivo≈

Praticar o desenvolvimento de rotas, controladores, migrations e relacionamentos no Laravel, garantindo que todas as funcionalidades estejam cobertas por testes automatizados.

## Antes de começar

Execute `composer install` para instalar as dependências do projeto, e `php artisan migrate --seed` para criar e popular as tabelas.

## Rúbricas dos Requisitos

### 1. Migration da tabela albums
- Crie uma migration para a tabela `albums` contendo as colunas: `id`, `name`, `year`, `url_img`, `created_at`, `updated_at`.
- Certifique-se de que a migration está registrada e pode ser migrada normalmente.
- O teste automatizado valida a existência da tabela e das colunas.

### 2. Migration da tabela musics com relação a albums
- Adicione a coluna `album_id` na migration de `musics`.
- Implemente a constraint de chave estrangeira relacionando `album_id` com a tabela `albums`.
- O teste automatizado verifica a existência da coluna e da relação.

### 3. Relacionamento N:1 entre Album e Music
- Implemente o relacionamento nas models:
	- `Album` deve ter o método `musics()` (hasMany).
	- `Music` deve ter o método `album()` (belongsTo).
- O teste automatizado verifica se o relacionamento está funcional.

### 4. Listagem de músicas exibindo o nome do álbum
- Na rota `/music`, cada música listada deve exibir o nome e imagem do álbum correspondentes.
- O teste automatizado verifica se o nome e imagem do álbum aparecem na listagem.

### 5. Exibição de músicas de um álbum
- No método `show` do controller de álbuns, exiba todas as músicas pertencentes ao álbum selecionado.
- O teste automatizado valida se as músicas do álbum são exibidas corretamente.

## Como executar os testes

Execute os testes automatizados para garantir que sua implementação está correta:

```bash
php artisan test --filter=AlbumMusicTest
```

Todos os testes devem passar para que sua solução seja considerada válida.

## Dicas

Bons estudos e boa implementação!


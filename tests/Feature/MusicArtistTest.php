<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Music;
use App\Models\Artist;
use Illuminate\Support\Facades\Schema;

class MusicArtistTest extends TestCase
{
    use RefreshDatabase;

    public function test_artists_table_and_columns_exist()
    {
        $this->assertTrue(Schema::hasTable('artists'));
        $this->assertTrue(Schema::hasColumns('artists', ['id', 'name', 'bio', 'created_at', 'updated_at']));
    }

    public function test_artist_music_pivot_table_exists()
    {
        $this->assertTrue(Schema::hasTable('artist_music'));
        $this->assertTrue(Schema::hasColumns('artist_music', ['artist_id', 'music_id']));
    }

    public function test_artist_and_music_models_have_belongs_to_many_relationships()
    {
        $album = \App\Models\Album::create([
            'name' => 'Álbum Teste',
            'year' => 2020,
            'url_img' => 'http://img.com/1.png',
        ]);
        $artist = Artist::create([
            'name' => 'Artista Teste',
            'bio' => 'Bio do artista.'
        ]);
        $music = Music::create([
            'title' => 'Música Teste',
            'artist' => 'Artista Teste',
            'album_id' => $album->id,
            'year' => 2020,
            'genre' => 'Pop'
        ]);
        $artist->musics()->attach($music);
        $this->assertTrue($artist->musics->contains($music));
        $this->assertTrue($music->artists->contains($artist));
    }

    public function test_can_associate_multiple_artists_to_music_and_vice_versa()
    {
        $album = \App\Models\Album::create([
            'name' => 'Álbum Teste',
            'year' => 2020,
            'url_img' => 'http://img.com/1.png',
        ]);
        $music1 = Music::create([
            'title' => 'Música 1',
            'artist' => 'Artista 1',
            'album_id' => $album->id,
            'year' => 2020,
            'genre' => 'Pop'
        ]);
        $music2 = Music::create([
            'title' => 'Música 2',
            'artist' => 'Artista 2',
            'album_id' => $album->id,
            'year' => 2021,
            'genre' => 'Rock'
        ]);
        $artist1 = Artist::create([
            'name' => 'Artista 1',
            'bio' => 'Bio 1'
        ]);
        $artist2 = Artist::create([
            'name' => 'Artista 2',
            'bio' => 'Bio 2'
        ]);
    $music1->artists()->attach([$artist1->id, $artist2->id]);
    $artist2->musics()->attach([$music2->id]); // Só associa o music2, evitando duplicidade
    $this->assertCount(2, $music1->artists);
    $this->assertCount(2, $artist2->musics);
    }

    public function test_music_list_displays_artists_names()
    {
        $album = \App\Models\Album::create([
            'name' => 'Álbum Teste',
            'year' => 2020,
            'url_img' => 'http://img.com/1.png',
        ]);
        $music = Music::create([
            'title' => 'Música Teste',
            'artist' => 'Artista Teste',
            'album_id' => $album->id,
            'year' => 2020,
            'genre' => 'Pop'
        ]);
        $artist1 = Artist::create([
            'name' => 'Artista 1',
            'bio' => 'Bio 1'
        ]);
        $artist2 = Artist::create([
            'name' => 'Artista 2',
            'bio' => 'Bio 2'
        ]);
        $music->artists()->attach([$artist1->id, $artist2->id]);
        $response = $this->get('/musics');
        $response->assertSee($artist1->name);
        $response->assertSee($artist2->name);
    }
}


//when you select a playlist all the audio player indexs and arrays... changes ----------------- 
//--CHANGES ON SHOW_PLAYLIST

//if(onclick){ restart interface ,save songs in the array}

let audioPlayer = document.getElementById('audio-player');
let currentSongIndex = 1; // Índice de la canción actual
let songs_button = document.getElementById("mySongs");
let myPlaylists = document.getElementById("myPlaylists");
let all_songs_div = document.getElementById('all_songs');
const songs_playlist = document.getElementById("songs_playlist");
let array_of_songs=[];

//songs and playlist first time entering the web 
show_playlists();
show_songs();
songs_playlist.textContent = "Your Songs and Playlists";






let currentPlaylist = null; // Variable para guardar la playlist actual

function show_playlists() {
    songs_playlist.textContent = "Your Playlists";
    for (const element of playList) {
        
        let playList_song_div = document.createElement('div');
        let playlist_title = element.name;
        const h1 = document.createElement('h1');
        h1.textContent = playlist_title;

        h1.addEventListener("click", event => {
            all_songs_div.innerHTML = ""; // Limpiar el contenedor principal

            const playlistHeader = document.createElement('h1');
            playlistHeader.textContent = playlist_title;
            all_songs_div.appendChild(playlistHeader);

            // Asignar la playlist actual
            currentPlaylist = element;

            let currentSongIndex = 0; // Índice para la lista de canciones de esta playlist

            for (const song of currentPlaylist.songs) {
                let songDiv = document.createElement('div');
                songDiv.classList.add('song');

                // Crear el título de la canción
                let songTitle = document.createElement('p');
                songTitle.className = "song-title";
                songTitle.textContent = song.title;

                // Crear la imagen de la canción
                let songImage = document.createElement('img');
                songImage.className = "image";
                songImage.src = `/img/${song.fileTitle}.jpg`; // Ruta de la imagen
                songImage.alt = `Imagen de ${song.title}`; // Texto alternativo

                songImage.addEventListener("click", () => {
                    loadSong(song);
                    currentSongIndex = currentPlaylist.songs.indexOf(song); // Actualiza el índice de la canción en esta playlist
                });

                // Agregar elementos al div de la canción
                songDiv.appendChild(songImage);
                songDiv.appendChild(songTitle);

                all_songs_div.appendChild(songDiv);
            }

            // Cambiar los botones 'next' y 'prev' para manejar el índice de esta playlist
            document.getElementById('next').addEventListener('click', () => {
                currentSongIndex = (currentSongIndex + 1) % currentPlaylist.songs.length;
                loadSong(currentPlaylist.songs[currentSongIndex]);
            });

            document.getElementById('prev').addEventListener('click', () => {
                currentSongIndex = (currentSongIndex - 1 + currentPlaylist.songs.length) % currentPlaylist.songs.length;
                loadSong(currentPlaylist.songs[currentSongIndex]);
            });

            // Evento para pasar a la siguiente canción cuando se termine la actual
            audioPlayer.addEventListener("ended", () => {
                currentSongIndex = (currentSongIndex + 1) % currentPlaylist.songs.length; // Avanzar al siguiente índice
                loadSong(currentPlaylist.songs[currentSongIndex]); // Cargar la siguiente canción
            });
        });

        playList_song_div.appendChild(h1);
        all_songs_div.appendChild(playList_song_div);
    }
}

//finished
function show_songs() {
    songs_playlist.textContent = "Your Songs";

    for (const song of songs) {
        let songDiv = document.createElement('div');
        songDiv.classList.add('song');

        // Crear un enlace que contenga la imagen
        let songLink = document.createElement('a');
        songLink.href = "#"; // Evitar que el enlace recargue la página

        // Crear la imagen
        let songImage = document.createElement('img');
        songImage.className = "image";
        songImage.src = `/img/${song.fileTitle + ".jpg"}`; // Ruta de la imagen
        songImage.alt = `Imagen de ${song.title}`; // Texto alternativo

        // Añadir un evento de clic a la imagen
        songImage.addEventListener('click', () => {
            loadSong(song);
            currentSongIndex = songs.indexOf(song);
            console.log("Índice actual:", currentSongIndex);
        });

        // Añadir la imagen al enlace
        songLink.appendChild(songImage);

        let songParagraph = document.createElement('p');
        songParagraph.className = "song-paragraph";
        songParagraph.textContent = `${song.title}`; // Formato del texto

        songDiv.appendChild(songLink);
        songDiv.appendChild(songParagraph);

        all_songs_div.appendChild(songDiv);
    }


    //general , DONT WORK ON PLAYLIST BECAUSE IT TAKES ALL THE SONGS 
    document.getElementById('next').addEventListener('click', () => {
        currentSongIndex = (currentSongIndex + 1) % songs.length;
        loadSong(songs[currentSongIndex]);
    });

    document.getElementById('prev').addEventListener('click', () => {
        currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length;
        loadSong(songs[currentSongIndex]);
    });


   

}

function loadSong(song) {

    let songImage = document.getElementById('song-image');
    songImage.className = "image";
    let songTitle = document.getElementById('song-title');
    let audioSource = document.getElementById('audio-source');

    songImage.src = `/img/${song.fileTitle}.jpg`; // Actualiza la imagen
    songTitle.textContent = song.title; // Actualiza el título
    audioSource.src = `/mp3/${song.fileTitle}.mp3`; // Actualiza la fuente de audio
    audioPlayer.load(); // Carga la nueva fuente de audio
    audioPlayer.play(); // Reproduce la canción automáticamente


}

//interface all songs
songs_button.addEventListener("click", event => {
    all_songs_div.innerHTML = ""; // Limpiar el div de canciones

    show_songs();
})
//interface playlist and the songs of each of them
myPlaylists.addEventListener("click", event => {
    all_songs_div.innerHTML = ""; // Limpiar el div de canciones

    show_playlists();
});



document.getElementById("searchInput").addEventListener("input", function() {
    let query = this.value.trim();
    let resultsDiv = document.getElementById("searchResults");

    if (query === "") {
        resultsDiv.innerHTML = ""; // Limpiar si el input está vacío
        resultsDiv.style.display = "none"; // Ocultar resultados
        return;
    }

    fetch(`/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {

            resultsDiv.innerHTML = ""; // Limpiar resultados anteriores

            if (!data.songs.length && !data.playlists.length) {
                resultsDiv.style.display = "none"; // Ocultar si no hay resultados
                return;
            }

            let resultsList = document.createElement("ul");
            resultsList.classList.add("search-list");

            // Mostrar canciones en los resultados
            data.songs.forEach(song => {
                let songItem = document.createElement("li");
                songItem.textContent = "🎵 " + song.title;
                songItem.classList.add("search-item");

                // Añadir evento al hacer click en una canción
                songItem.addEventListener("click", () => {
                    loadSong(song); // Cargar la canción seleccionada
                });

                resultsList.appendChild(songItem);
            });

            // Mostrar playlists en los resultados
            data.playlists.forEach(playlist => {
                let playlistItem = document.createElement("li");
                playlistItem.textContent = "📜 " + playlist.name;
                playlistItem.classList.add("search-item");

                // Añadir evento al hacer click en una playlist
                playlistItem.addEventListener("click", () => {
                    console.log(playlist);
                    loadPlaylist(playlist); // Cargar la playlist seleccionada
                });

                resultsList.appendChild(playlistItem);
            });

            resultsDiv.appendChild(resultsList);
            resultsDiv.style.display = "block"; // Mostrar resultados
        })
        .catch(error => {
            console.error("Error en la búsqueda:", error);
            resultsDiv.innerHTML = "<p>Error al buscar resultados</p>";
            resultsDiv.style.display = "block"; // Mostrar error
        });
});
// Cargar playlist seleccionada
function loadPlaylist(playlist) {
    all_songs_div.innerHTML = ""; // Limpiar la interfaz principal

    // Mostrar el nombre de la playlist
    const playlistHeader = document.createElement('h1');
    playlistHeader.textContent = playlist.name;
    all_songs_div.appendChild(playlistHeader);

    // Recorrer las playlists y mostrar las canciones de la playlist seleccionada
    for (const pl of playList) {
        // Compara la playlist seleccionada con las playlists disponibles
        if (pl.name === playlist.name) {
            // Recorrer las canciones de la playlist seleccionada
            for (const song of pl.songs) {
                let songDiv = document.createElement('div');
                songDiv.classList.add('song');

                // Crear la imagen de la canción
                let songImage = document.createElement('img');
                songImage.className = "image";
                songImage.src = `/img/${song.fileTitle}.jpg`; // Usando fileTitle para la imagen
                songImage.alt = `Imagen de ${song.title}`; // Texto alternativo

                // Añadir evento para cargar la canción
                songImage.addEventListener('click', () => {
                    loadSong(song);
                });

                // Crear el título de la canción
                let songTitle = document.createElement('p');
                songTitle.className = "song-title";
                songTitle.textContent = song.title;

                // Agregar la imagen y el título al div de la canción
                songDiv.appendChild(songImage);
                songDiv.appendChild(songTitle);

                all_songs_div.appendChild(songDiv);
            }
            break; // Salir del ciclo una vez se encuentra la playlist seleccionada
        }
    }
}

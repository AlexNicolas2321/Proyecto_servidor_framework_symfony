
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










let Playlists_user = document.getElementById("Playlists_user");

Playlists_user.addEventListener("click", event => {
    all_songs_div.innerHTML = ""; // Limpiar

    fetch('/api/playlists')
        .then(response => {
            if (response.status === 401) {
                document.getElementById('playlists').innerHTML = "No tienes acceso a las playlists.";
                return [];
            }
            return response.json();
        })
        .then(playlists => {
            const playlistContainer = document.getElementById('all_songs');
            playlistContainer.innerHTML = "";  // Limpiar antes de agregar

            // Mostrar las playlists en el contenedor
            playlists.forEach(playlist => {
                const item = document.createElement('div');
                item.textContent = playlist.name;
                item.setAttribute('data-id', playlist.id);
                item.classList.add('playlist-item');
                playlistContainer.appendChild(item);

                // Mostrar las canciones si la playlist tiene canciones
                if (playlist.canciones && playlist.canciones.length > 0) {
                    const cancionesContainer = document.createElement('div');
                    playlist.canciones.forEach(song => {
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
                    });
                    item.appendChild(cancionesContainer);
                }
            });
        })
        .catch(error => console.error('Error:', error));
});











document.getElementById('createPlaylistButton').addEventListener('click', function() {
    
    
    // Limpiar cualquier formulario existente
    all_songs_div.innerHTML = '';

    // Crear el formulario
    const form = document.createElement('form');
    form.id = 'createPlaylistForm';

    // Crear el campo para el nombre de la playlist
    const nameLabel = document.createElement('label');
    nameLabel.innerText = 'Nombre de la Playlist:';
    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.id = 'playlistName';
    nameInput.required = true;
    form.appendChild(nameLabel);
    form.appendChild(nameInput);

    // Crear los checkboxes para las canciones
    const songLabel = document.createElement('label');
    songLabel.innerText = 'Selecciona las canciones:';
    form.appendChild(songLabel);
    
    // Suponiendo que `songs` es un array de objetos con los títulos de las canciones
    songs.forEach(function(song) {
        const checkboxDiv = document.createElement('div');
        
        // Crear un checkbox por cada canción
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'songTitles[]';
        checkbox.value = song.title; // Usar el 'title' de cada canción
        const checkboxLabel = document.createElement('label');
        checkboxLabel.innerText = song.title; // Mostrar el 'title' como texto en el label
        checkboxDiv.appendChild(checkbox);
        checkboxDiv.appendChild(checkboxLabel);
        form.appendChild(checkboxDiv);
    });

    // Crear el botón de enviar
    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.innerText = 'Crear Playlist';
    form.appendChild(submitButton);

    // Añadir el formulario al contenedor
    all_songs_div.appendChild(form);

    // Evento para enviar el formulario
    form.addEventListener('submit', function(event) {
        alert("entro");
        event.preventDefault();
        
        // Obtener el nombre de la playlist y las canciones seleccionadas
        const playlistName = document.getElementById('playlistName').value;
        const selectedSongs = Array.from(document.querySelectorAll('input[name="songTitles[]"]:checked'))
                                    .map(checkbox => checkbox.value);
                                    console.log('Selected song titles:', selectedSongs);

        // Enviar los datos al backend
        fetch('/api/create_playlists', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: playlistName,
                songTitles: selectedSongs
            })
        })
        .then(response => {
            // Si la respuesta no es OK (status 200-299)
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.error || 'Error desconocido');
                });
            }
            return response.json(); // Continuamos solo si la respuesta es válida
        })
        .then(data => {
            alert(data.message); // Mostrar mensaje de éxito
            location.reload(); // Recargar para mostrar la nueva playlist
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error: " + error.message); // Mostrar el mensaje de error al usuario
        });
        
    });
});

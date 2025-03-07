
document.addEventListener("DOMContentLoaded", function () {

    let logged_in_div= document.getElementById('logged_in');

    console.log("isLoggedIn:", isLoggedIn);
    console.log("username:", username);

    if (isLoggedIn == true) { // Verifica si la sesión está activa
        logged_in_div.innerHTML = `
            <p id="welcome_paragraph">Welcome, ${username}</p>
            <button id="logoutButton">Logout</button>
        `;
    }else{
        let createPlaylistButton= document.getElementById("createPlaylistButton");
        createPlaylistButton.style.display="none";
        let Playlists_user = document.getElementById("Playlists_user");
        Playlists_user.style.display="none";

    }
    
    // Agregar evento al botón de logout
    document.getElementById("logoutButton").addEventListener("click", function () {
        window.location.href = "/logout"; // Asegúrate de que esta ruta esté definida en Symfony
    });



});






let audioPlayer = document.getElementById('audio-player');
let currentSongIndex = 0; // Índice de la canción actual
let songs_button = document.getElementById("mySongs");
let myPlaylists = document.getElementById("myPlaylists");
let all_songs_div = document.getElementById('all_songs');
let all_playlists_div = document.getElementById('all_playlists');
let h1_Playlists_title = document.getElementById('h1_Playlists_title');
let h1_Songs_title = document.getElementById('h1_Songs_title');
let go_home= document.getElementById("img_spotify");

let createPlaylistButton= document.getElementById("createPlaylistButton");
let Playlists_user = document.getElementById("Playlists_user");

let array_of_songs = [];


audioPlayer_logic();
show_playlists();
show_songs();


go_home.addEventListener("click",event=>{
    h1_Songs_title.style.display="block";
    h1_Playlists_title.style.display="block";  
    show_playlists();
    show_songs();
})


function show_songs() {
    all_songs_div.innerHTML="";
    array_of_songs.length = 0;
    array_of_songs.push(...songs);
    console.log(array_of_songs);

    for (const song of songs) {
        let songDiv = document.createElement('div');
        songDiv.classList.add('song');

        // Crear un enlace que contenga la imagen
        let songLink = document.createElement('a');
        songLink.href = "#"; // Evitar que el enlace recargue la página

        // Crear la imagen
        let songImage = document.createElement('img');
        songImage.className = "image";
        songImage.src = `/img/${song.fileTitle}.jpg`; // Ruta de la imagen
        songImage.alt = `Imagen de ${song.title}`; // Texto alternativo

        // Crear el título de la canción
        let songParagraph = document.createElement('p');
        songParagraph.className = "song_paragraph";
        songParagraph.textContent = ` 🎵 ${song.title}`; // Formato del texto

        // Añadir un evento de clic tanto a la imagen como al título
        const loadSongAndSetIndex = () => {
            loadSong(song);
            currentSongIndex = array_of_songs.indexOf(song);
            console.log("Índice actual:", currentSongIndex);
        };

        // Asignamos el evento tanto a la imagen como al título
        songImage.addEventListener('click', loadSongAndSetIndex);
        songParagraph.addEventListener('click', loadSongAndSetIndex);

        // Añadir la imagen al enlace
        songLink.appendChild(songImage);

        songDiv.appendChild(songLink);
        songDiv.appendChild(songParagraph);

        all_songs_div.appendChild(songDiv);
    }
}



function loadSong(song) {
    
    // Asegúrate de que el elemento con el id "song-image" tenga la clase "image"
    let songImage = document.getElementById('song-image');
    let songTitle = document.getElementById('song-title');
    let audioPlayer = document.getElementById('audio-player'); // Asegurar que el reproductor existe

    // Si el elemento no tiene la clase 'image', la añadimos
    songImage.classList.add('image');
    
    // Actualizar imagen y título
    songImage.src = `/img/${song.fileTitle}.jpg`;
    songTitle.textContent = ` 🎵  ${song.title}`;

    // Asignar la nueva fuente de audio desde el servidor
    audioPlayer.src = `/Song/${song.fileTitle}/play`;
    
    // Cargar y reproducir la canción
    audioPlayer.load();
    audioPlayer.play().catch(err => {
        console.error("Error al reproducir la canción:", err);
        alert("No se pudo reproducir la canción. Asegúrate de que el archivo existe en el servidor.");
    });

    // Manejo de errores en la carga
    audioPlayer.onerror = function () {
        console.error("Error al cargar la canción.");
        alert("No se pudo cargar la canción.");
    };
}




function audioPlayer_logic() {
    // Botón siguiente
    document.getElementById('next').addEventListener('click', () => {
        currentSongIndex = (currentSongIndex + 1) % array_of_songs.length;
        loadSong(array_of_songs[currentSongIndex]);
    });

    // Botón anterior
    document.getElementById('prev').addEventListener('click', () => {
        currentSongIndex = (currentSongIndex - 1 + array_of_songs.length) % array_of_songs.length;
        loadSong(array_of_songs[currentSongIndex]);
    });

    // Evento para pasar a la siguiente canción cuando se termine la actual
    audioPlayer.addEventListener("ended", () => {
        currentSongIndex = (currentSongIndex + 1) % array_of_songs.length; // Avanzar al siguiente índice
        loadSong(array_of_songs[currentSongIndex]); // Cargar la siguiente canción
    });

}

function show_playlists() {
    all_playlists_div.innerHTML="";
    for (const element of playList) {

        let playList_song_div = document.createElement('div');
        let playlist_title = element.name;
        const h1 = document.createElement('h1');
        h1.textContent = `📃🎶 ${playlist_title}`;
        h1.className = "playlist_paragraph";

        h1.addEventListener("click", event => {
            h1_Songs_title.style.display="none";
            all_playlists_div.innerHTML = ""; // Limpiar el contenedor principal
            all_songs_div.innerHTML="";
            const playlistHeader = document.createElement('h1');
            playlistHeader.textContent = playlist_title;
            playlistHeader.className = "playlist_paragraph_no_hover";
            all_playlists_div.appendChild(playlistHeader);

            const div = document.createElement("div");
            all_playlists_div.appendChild(div);
            // Asignar la playlist actual
            array_of_songs.length = 0;

            for (const song of element.songs) {
                array_of_songs.push(song);

                let songDiv = document.createElement('div');
                songDiv.classList.add('song'); // Usar la misma clase que en show_songs()

                // Crear el título de la canción
                let songTitle = document.createElement('p');
                songTitle.classList.add('song_paragraph'); // Usar la misma clase para el título
                songTitle.textContent = ` 🎵 ${song.title}`;

                // Crear la imagen de la canción
                let songImage = document.createElement('img');
                songImage.className = "image";
                songImage.src = `/img/${song.fileTitle}.jpg`; // Ruta de la imagen
                songImage.alt = `Imagen de ${song.title}`; // Texto alternativo

                // Función para cargar la canción y actualizar el índice
                const loadSongAndSetIndex = () => {
                    loadSong(song);
                    currentSongIndex = array_of_songs.indexOf(song);
                    console.log("Índice actual:", currentSongIndex);
                };

                // Añadir el evento de clic tanto a la imagen como al título
                songImage.addEventListener("click", loadSongAndSetIndex);
                songTitle.addEventListener("click", loadSongAndSetIndex);

                // Agregar elementos al div de la canción
                songDiv.appendChild(songImage);
                songDiv.appendChild(songTitle);

                // Añadir la canción a la lista
                all_playlists_div.appendChild(songDiv);
            }
        });

        playList_song_div.appendChild(h1);
        all_playlists_div.appendChild(playList_song_div);
    }
}

function show_single_playlist(playlist) {
    all_playlists_div.innerHTML = "";
    h1_Songs_title.style.display = "none";
    all_songs_div.innerHTML = "";
    let playList_song_div = document.createElement('div');
    let playlist_title = playlist.name;
    const h1 = document.createElement('h1');
    h1.textContent = `📃🎶 ${playlist_title}`;
    h1.className = "playlist_paragraph";

    h1.addEventListener("click", event => {
        h1_Songs_title.style.display = "none";
        all_playlists_div.innerHTML = ""; // Limpiar el contenedor principal
        all_songs_div.innerHTML = "";
        const playlistHeader = document.createElement('h1');
        playlistHeader.textContent = playlist_title;
        playlistHeader.className = "playlist_paragraph_no_hover";
        all_playlists_div.appendChild(playlistHeader);

        const div = document.createElement("div");
        all_playlists_div.appendChild(div);
        // Asignar la playlist actual
        array_of_songs.length = 0;
        console.log(playlist);
        for (const song of playlist.songs) {
            array_of_songs.push(song);

            let songDiv = document.createElement('div');
            songDiv.classList.add('song'); // Usar la misma clase que en show_songs()

            // Crear el título de la canción
            let songTitle = document.createElement('p');
            songTitle.classList.add('song_paragraph'); // Usar la misma clase para el título
            songTitle.textContent = ` 🎵 ${song.title}`;

            // Crear la imagen de la canción
            let songImage = document.createElement('img');
            songImage.className = "image";
            songImage.src = `/img/${song.fileTitle}.jpg`; // Ruta de la imagen
            songImage.alt = `Imagen de ${song.title}`; // Texto alternativo

            // Función para cargar la canción y actualizar el índice
            const loadSongAndSetIndex = () => {
                loadSong(song);
                currentSongIndex = array_of_songs.indexOf(song);
                console.log("Índice actual:", currentSongIndex);
            };

            // Añadir el evento de clic tanto a la imagen como al título
            songImage.addEventListener("click", loadSongAndSetIndex);
            songTitle.addEventListener("click", loadSongAndSetIndex);

            // Agregar elementos al div de la canción
            songDiv.appendChild(songImage);
            songDiv.appendChild(songTitle);

            // Añadir la canción a la lista
            all_playlists_div.appendChild(songDiv);
        }
    });

    playList_song_div.appendChild(h1);
    all_playlists_div.appendChild(playList_song_div);
}





//interface all songs
songs_button.addEventListener("click", event => {
    all_songs_div.innerHTML = ""; // Limpiar el div de canciones
    all_playlists_div.innerHTML="";
    h1_Playlists_title.style.display="none";  
    h1_Songs_title.style.display="block";

    show_songs();
})
//interface playlist and the songs of each of them
myPlaylists.addEventListener("click", event => {
    all_songs_div.innerHTML = ""; // Limpiar el div de canciones
    all_playlists_div.innerHTML="";
    h1_Songs_title.style.display="none";
    h1_Playlists_title.style.display="block";  

    show_playlists();
});

/*$request->query es un objeto que contiene todos los parámetros de consulta de la URL.
 Por ejemplo, si la URL es algo como https://miapp.com/search?q=rock,
//  entonces $request->query contendría los parámetros de consulta: ['q' => 'rock']*/ 

document.getElementById("searchInput").addEventListener("input", function () {
    let query = this.value.trim();
    let resultsDiv = document.getElementById("searchResults");

    if (query === "") {
        resultsDiv.innerHTML = ""; // Limpiar si el input está vacío
        resultsDiv.style.display = "none"; // Ocultar resultados
        return;
    }
    //query has what the user is searching while encodeURIcomponente is just a way to read the special characters correctly
    //q is a value setted for the mysql query ,show he takes the value of query
    fetch(`/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {console.log(data);

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
                    show_single_playlist(playlist); // Cargar la playlist seleccionada
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











Playlists_user.addEventListener("click", event => {
    all_songs_div.innerHTML = ""; // Limpiar
    h1_Songs_title.style.display="none";
    all_playlists_div.innerHTML="";

    fetch('/api/playlists')
        .then(response => {
            if (response.status === 401) {
                document.getElementById('playlists').innerHTML = "No tienes acceso a las playlists.";
                return [];
            }
            return response.json();
        })
        .then(playlists => {
            console.log("owner",playlists);
            const playlistContainer = document.getElementById('all_songs');
            playlistContainer.innerHTML = "";  // Limpiar antes de agregar

            // Mostrar las playlists en el contenedor
            playlists.forEach(playlist => {
                const item = document.createElement('div');
                item.textContent = playlist.name;
                item.setAttribute('data-id', playlist.id);
                item.classList.add('playlist-item');
                playlistContainer.appendChild(item);

                let div= document.createElement("div");
                playlistContainer.append(div);
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
                        songParagraph.className = "song_paragraph";
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











document.getElementById('createPlaylistButton').addEventListener('click', function () {


    // Limpiar cualquier formulario existente
    all_songs_div.innerHTML = '';
    h1_Songs_title.style.display="none";
    h1_Playlists_title.style.display="none";
    all_playlists_div.innerHTML="";
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
    songs.forEach(function (song) {
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
    submitButton.classList.add("button");
    form.appendChild(submitButton);

    // Añadir el formulario al contenedor
    all_songs_div.appendChild(form);

    // Evento para enviar el formulario
    form.addEventListener('submit', function (event) {
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


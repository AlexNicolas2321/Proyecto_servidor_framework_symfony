let currentSongIndex = 0; // Índice de la canción actual
let songs_button =document.getElementById("mySongs");
let myPlaylists= document.getElementById("myPlaylists");
let all_songs_div = document.getElementById('all_songs');
const songs_playlist = document.getElementById("songs_playlist");

myPlaylists.addEventListener("click", event => {
    all_songs_div.innerHTML = ""; // Limpiar el div de canciones
    songs_playlist.textContent = "Your Playlists";
    for (const element of playList) {
        let playList_song_div = document.createElement('div');

        const h1 = document.createElement('h1');
        h1.textContent = element.name; // Cambia el texto según sea necesario
        playList_song_div.appendChild(h1); // Agregar el nombre de la lista de reproducción

        // Iterar sobre las canciones en la lista de reproducción
        for (const element1 of element.songs) {
            const songTitle = document.createElement('div'); // Crear un div para el título de la canción
            songTitle.textContent = element1.title; // Establecer el texto del div como el título de la canción
            playList_song_div.appendChild(songTitle); // Agregar el div de la canción al div de la lista de reproducción

            // Agregar un <br> después de cada canción
            playList_song_div.appendChild(document.createElement('br'));
        }

        // Agregar el div de la lista de reproducción al contenedor principal
        all_songs_div.appendChild(playList_song_div);
    }
});


//console.log(playList);




function loadSong(song) {
    let songImage = document.getElementById('song-image');
    songImage.className="image";
    let songTitle = document.getElementById('song-title');
    let audioSource = document.getElementById('audio-source');
    let audioPlayer = document.getElementById('audio-player');

    songImage.src = `/img/${song.fileTitle}.jpg`; // Actualiza la imagen
    songTitle.textContent = song.title; // Actualiza el título
    audioSource.src = `/mp3/${song.fileTitle}.mp3`; // Actualiza la fuente de audio
    audioPlayer.load(); // Carga la nueva fuente de audio
    audioPlayer.play(); // Reproduce la canción automáticamente
}

songs_button.addEventListener("click",event =>{

    all_songs_div.innerHTML="";
    songs_playlist.textContent = "Your Songs";

// Iterar sobre cada canción y crear un párrafo con el título y el ID
for (const song of songs) {
    // Crear un nuevo div para la canción
    let songDiv = document.createElement('div');
    songDiv.classList.add('song'); // Puedes agregar una clase para estilos si lo deseas

    // Crear un enlace que contenga la imagen
    let songLink = document.createElement('a');
    songLink.href = "#"; // Evitar que el enlace recargue la página

    // Crear la imagen
    let songImage = document.createElement('img');
    songImage.className="image";
    songImage.src = `/img/${song.fileTitle +".jpg"}`; // Ruta de la imagen
    songImage.alt = `Imagen de ${song.title}`; // Texto alternativo

    // Añadir un evento de clic a la imagen
    songImage.addEventListener('click', () => {
        loadSong(song); // Cambia la canción al hacer clic en la imagen
        currentSongIndex = songs.indexOf(song); // Actualiza el índice de la canción actual
        console.log("Índice actual:", currentSongIndex); // Para verificar el índice en la consola
    });

    // Añadir la imagen al enlace
    songLink.appendChild(songImage);

    // Crear un párrafo con el título y el ID
    let songParagraph = document.createElement('p');
    songParagraph.className="song-paragraph";
    songParagraph.textContent = `${song.title}`; // Formato del texto

    // Añadir el párrafo y el enlace al div de la canción
    songDiv.appendChild(songLink);
    songDiv.appendChild(songParagraph);

    // Añadir el div de la canción al div general
    all_songs_div.appendChild(songDiv);
}


// Cargar la primera canción al abrir la página
loadSong(songs[currentSongIndex]);

document.getElementById('next').addEventListener('click', () => {
    currentSongIndex = (currentSongIndex + 1) % songs.length; // Incrementa el índice
    loadSong(songs[currentSongIndex]); // Carga la siguiente canción
});

document.getElementById('prev').addEventListener('click', () => {
    currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length; // Decrementa el índice
    loadSong(songs[currentSongIndex]); // Carga la canción anterior
});
})


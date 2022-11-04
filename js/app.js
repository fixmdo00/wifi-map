if ('serviceWorker' in navigator){
    navigator.serviceWorker.register('/sw.js')
    .then((reg) => console.log('servie worker registered', reg))
    .catch((err) => console.log('service worker is not regsitered', err));
}
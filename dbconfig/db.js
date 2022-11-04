//realtime-listener
db.collection('titik').onSnapshot((snapshot) => {
    // console.log(snapshot.docChanges());

    // snapshot.docChanges().forEach( change => {
    //     console.log(change, change._delegate, change._delegate.doc.data(), change._delegate.doc.id);    
    // })
})

function upload(data){ 
    db.collection("titik").add(data)
    .then((docRef) => {
        console.log("Document written with ID: ", docRef.id);
    })
    .catch((error) => {
        console.error("Error adding document: ", error);
    });
}
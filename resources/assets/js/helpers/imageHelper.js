const firstUrl = '/api/v1';


export async function temporaryImageUpload(files) {    
    var uploadResponse = [];
    if (files.length == 0) return uploadResponse;
    var formData = new FormData;
    for (let index = 0; index < files.length; index++) {
        formData.append('images[]', files[index]);       
    }
    await axios.post(firstUrl + '/temporaryImageUpload', formData)
        .then((response) => {
            if (response.data.success)
                uploadResponse = response.data.urls;
        })
        .catch((error) => {
            uploadResponse = error;
        })
    return uploadResponse;
}

export async function removeTemporaryImage(imageUrl) {    
    var uploadResponse = '';
    var formData = new FormData;
    formData.append('image', imageUrl); 
    await axios.post(firstUrl + '/removeTemporaryImage', formData)
        .then((response) => {
            uploadResponse = response.data;
        })
        .catch((error) => {
            uploadResponse = error;
        })
    return uploadResponse;
}

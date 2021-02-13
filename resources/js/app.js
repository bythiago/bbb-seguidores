require('./bootstrap');
window.swal = require('sweetalert2');

var data = '';

window.axios.interceptors.request.use((config) => {
    window.swal.fire({
        title: 'Loading Brothers',
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
            window.swal.showLoading()
        },
    });
    
    return config;
}, (error) => {
    return Promise.reject(error);
});

function faIcon(item){
    let followed = [];
    followed[0] = parseFloat(item[0].followed);
    followed[1] = parseFloat(item[1].followed);
    return `<span class="badge ${followed[0] >= followed[1] ? 'bg-success' : 'bg-danger'}">
        ${followed[0] >= followed[1] ? '<i class="fas fa-thumbs-up"></i>' : '<i class="fas fa-thumbs-down"></i>'}
        ${followed[0] - followed[1]}
    </span>`;
}

window.axios.get('/api').then(function (response) {
    window.swal.closeModal();
    $.map(response.data, function(item){
        data +=`<div class="card col-lg-2 col-4 mt-5 text-center" style="padding: 0;">
            <img src="data:image/jpeg;base64,${item[0].profile_url}" class="card-img-top" alt="${item[0].username}">
            <div class="card-body">
                <span class="badge bg-info">@${item[0].username}</span>
                <span class="badge bg-success">${item[0].followed_formated}</span>
                <div class="card-text">
                    ${faIcon(item)}
                </div>
            </div>
        </div>`;
    });

    $("#main").append(data);
})
.catch(function (error) {
    console.log(error);
});
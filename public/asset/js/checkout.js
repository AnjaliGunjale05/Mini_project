document.addEventListener('DOMContentLoaded', function () {

    const country = document.getElementById('country');
    const state = document.getElementById('state');
    const city = document.getElementById('city');

   
    country.addEventListener('change', function () {
        fetch(`/states/${this.value}`)
            .then(res => res.json())
            .then(data => {

                state.innerHTML = '<option value="">Select State</option>';
                city.innerHTML = '<option value="">Select City</option>';
                data.forEach(s => {
                    state.innerHTML += `<option value="${s.id}"> ${s.name} </option>`;
                });

            });
    });


    state.addEventListener('change', function () {

        fetch(`/cities/${this.value}`)
            .then(res => res.json())
            .then(data => {

                city.innerHTML = '<option value="">Select City</option>';

                data.forEach(c => {
                    city.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                });
            });
    });

});
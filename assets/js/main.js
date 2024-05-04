const form = document.querySelector("form");
const campos = document.querySelectorAll(".perfil__dados");
const dialog = document.querySelector('.perfil__form');
const editar_btn = document.querySelector('.perfil__btn--editar');
const select = document.querySelector('#form__estados');
const username = document.querySelector('.perfil__username');

if (username.innerText.length > 15)
    username.innerText = username.innerText.slice(0, 15) + '...';

const openModal = () => {
    dialog.showModal();
    dialog.style.animation = 'var(--animation)';
    document.body.style.overflow = 'hidden';
}

const closeModal = () => {
    dialog.close();
    document.body.style.overflow = 'auto';
}

editar_btn.addEventListener("click", () => openModal());

document.querySelector("#file").addEventListener("change", () => {
    label = document.querySelector(".form__label--file");
    label.querySelectorAll("span")[0].innerText = "check";
    label.querySelectorAll("span")[1].innerText = "Foto adicionada";
});

const estados = [];
(async () => {
    await fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados')
    .then(response => response.json())
    .then(data => {
            data.forEach(estado => {
                estados.push({
                    sigla: estado.sigla
                });
            });
        })
        .catch(error => {
            console.error(`Ocorreu um erro ao buscar os dados dos estados: ${error}`);
        });
        estados.forEach(uf => {
            option = document.createElement('option');
            option.value = uf.sigla;
            option.innerText = uf.sigla;
            select.appendChild(option);
        });
})();

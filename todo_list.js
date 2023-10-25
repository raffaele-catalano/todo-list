document.addEventListener('DOMContentLoaded', function () {
    // riferimenti agli elementi del DOM
    const taskForm = document.getElementById('taskForm');
    const taskInput = document.getElementById('taskTitle');
    // const dueDateInput = document.getElementById('dueDate');
    const shareTaskCheckbox = document.getElementById('shareTask');
    const groupSelection = document.getElementById('groupSelection');
    const groupSelect = document.getElementById('groupSelect');
    const taskList = document.getElementById('taskList');

    // selezione del gruppo in base al checkbox
    shareTaskCheckbox.addEventListener('change', function () {
        groupSelection.style.display = this.checked ? 'block' : 'none';
    });

    // sottomissione del modulo
    taskForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Impedisci il comportamento predefinito del modulo

        // dati del modulo
        const title = taskInput.value;
        const dueDateInput = document.getElementById('dueDate'); // Otteni l'elemento input
        const dueDate = formatDueDate(dueDateInput.value); // Formatta la data
        const shareTask = shareTaskCheckbox.checked;
        const groupId = shareTask ? groupSelect.value : null;

        // richiesta POST per aggiungere un nuovo task al database utilizzando fetch
        fetch('add_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                title: title,
                dueDate: dueDate,
                groupId: groupId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // se il task è stato aggiunto con successo, aggiorna la visualizzazione
                loadTasks();
                taskForm.reset(); // resetta il modulo per ripulire i campi
            } else {
                alert('Errore durante l\'aggiunta del task: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Errore nella richiesta:', error);
        });
    });

    loadTasks();

    loadGroups();

    // funzione per formattare la data come "YYYY-MM-DD HH:MM:SS"
    function formatDueDate(isoDate) {
        const date = new Date(isoDate);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}-${month}-${year} ${hours}:${minutes}`;
    }

    // funzione per caricare le attività dalla tua API
    function loadTasks() {
        fetch('get_tasks.php')
        .then(response => response.json())
        .then(data => {
            // svuota la lista attuale
            taskList.innerHTML = '';

            // cicla attraverso le attività e crea righe della tabella
            data.forEach((task, index) => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <th scope="row">${index + 1}</th>
                    <td>${task.title}</td>
                    <td>${task.due_date}</td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkbox-${task.id}" ${task.completed ? 'checked' : ''}>
                        </div>
                    </td>
                `;
                taskList.appendChild(newRow);

                // gestore di eventi per il cambiamento della checkbox
                const checkbox = newRow.querySelector(`#checkbox-${task.id}`);
                checkbox.addEventListener('change', function () {
                    updateTaskStatus(task.id, this.checked);
                });
            });
        })
        .catch(error => {
            console.error('Errore nel recupero delle attività:', error);
        });
    }

    // funzione per caricare i gruppi dal database e popolare il menu a discesa
    function loadGroups() {
        fetch('get_groups.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(group => {
                const option = document.createElement('option');
                option.value = group.id;
                option.textContent = group.group_name;
                groupSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Errore nel recupero dei gruppi:', error);
        });
    }

    // funzione per aggiornare lo stato del task
    function updateTaskStatus(taskId, completed) {
        fetch('update_task_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: taskId,
                completed: completed ? 1 : 0,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // task aggiornato con successo
            } else {
                alert('Errore durante l\'aggiornamento dello stato del task: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Errore nella richiesta:', error);
        });
    }
});

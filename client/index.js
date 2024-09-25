const baseURL =
  "http://localhost/ToDo-php/server/routes/routes.php?route=tasks";

const inputTask = document.getElementById("task_name");

let tasks = [];

const getData = () => {
  if (tasks.length > 0) {
    return new Promise((resolve) => resolve(tasks));
  }

  return new Promise((resolve, reject) => {
    fetch(`${baseURL}`)
      .then((res) => res.json())
      .then((data) => {
        tasks = data;
        resolve(data);
      })
      .catch((error) => reject(error));
  });
};

const handleAction = (id, status_id) => {
  console.log({ id, status_id });

  return new Promise((resolve, reject) => {
    fetch(`${baseURL}`, {
      method: "PUT",
      body: JSON.stringify({ id, status_id }),
    })
      .then((res) => res.json())
      .then((data) => {
        tasks = [];
        resolve(data);
        start();
      })
      .catch((error) => reject(error));
  });
};

const handleDelete = (id) => {
  return new Promise((resolve, reject) => {
    if (!confirm("¿Estas seguro de eliminar esta tarea?")) {
      return;
    }
    fetch(`${baseURL}`, {
      method: "DELETE",
      body: JSON.stringify({ id }),
    })
      .then((res) => res.json())
      .then((data) => {
        tasks = [];
        resolve(data);
        start();
      })
      .catch((error) => reject(error));
  });
};

const downloadCSV = () => {
  const headers = ["ID", "Nombre", "Fecha de Creación", "Estado"];
  let csvContent = "data:text/csv;charset=utf-8,\uFEFF";

  csvContent += headers.join(";") + "\n";

  tasks.forEach((task) => {
    const row = [task.id, task.name, task.creation_date, task.status_name];
    csvContent += row.join(";") + "\n";
  });

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "tareas.csv");
  document.body.appendChild(link);

  link.click();
  document.body.removeChild(link);
};

const createTask = () => {
  const name = document.getElementById("task_name").value;
  if (name === "") {
    alert("El nombre de la tarea es obligatorio");
    return;
  }

  return new Promise((resolve, reject) => {
    fetch(`${baseURL}`, {
      method: "POST",
      body: JSON.stringify({ name }),
    })
      .then((res) => res.json())
      .then((data) => {
        resolve(data);
        tasks = [];
        inputTask.value = "";
        start();
      })
      .catch((error) => reject(error));
  });
};

const start = () => {
  getData()
    .then((data) => {
      renderTable({
        status: "0",
        data,
        message: "Informacion cargada correctamente",
      });
    })
    .catch((error) => {
      console.log(error);
      renderTable({
        status: "1",
        data: [],
        message: "No se pudo cargar la informacion",
      });
    });
};
start();

const renderTable = ({ status, message, data }) => {
  const tableBody = document.getElementById("table-body");
  console.log({ status, message, data });

  if (status !== "0") {
    tableBody.innerHTML = `
        <tr><td colspan="6"> 
            <h1 class="text-center bg-danger p-3 text-white" style="--bs-bg-opacity: .5;">
                ${message}
            </h1>
        </td></tr>`;
    return;
  }

  if (data.length === 0) {
    tableBody.innerHTML = `
        <tr><td colspan="6"> 
            <h1 class="text-center bg-warning p-3 text-white" style="--bs-bg-opacity: .5;">
                No hay tareas registradas
            </h1>
        </td></tr>`;
    return;
  }

  let table = "";

  data.forEach((task) => {
    const { actions, statusClass } = getButttons(task.status_name, task.id);
    const row = document.createElement("tr");
    row.className = "text-center align-middle ";
    row.innerHTML = `
            <td>${task.id}</td>
            <td>${task.name}</td>
            <td>${task.creation_date}</td>
            <td> <h5 class ="${statusClass} p-1 text-white text-center rounded-2">${task.status_name}</h5></td>
            <td class="text-white">
                ${actions}
            </td>
            `;
    table += row.outerHTML;
  });

  tableBody.innerHTML = table;
};

const getButttons = (name, id) => {
  let statusClass = "";
  let actions = "";

  switch (name) {
    case "Pendiente":
      statusClass = "bg-warning";
      actions = `
            <button onclick="handleAction(${id}, 2)" class="btn btn-success">Iniciar</button>
            <button onclick="handleAction(${id}, 4)" class="btn btn-danger">Cancelar</button>
            <button onclick="handleDelete(${id})" class="btn btn-danger">Eliminar</button>
        `;
      break;
    case "Proceso":
      statusClass = "bg-info";
      actions = `
        <button onclick="handleAction(${id}, 3)" class="btn btn-success">Completar</button>
        <button onclick="handleAction(${id}, 4)" class="btn btn-danger">Cancelar</button>    
        <button onclick="handleDelete(${id})" class="btn btn-danger">Eliminar</button>
        `;
      break;
    case "Completada":
      statusClass = "bg-success";
      actions = `
                  <button onclick="handleDelete(${id})" class="btn btn-danger">Eliminar</button>
              `;
      break;
    case "Cancelada":
      statusClass = "bg-danger";
      actions = `
                  <button onclick="handleDelete(${id})" class="btn btn-danger">Eliminar</button>
              `;
      break;
    default:
      actions = "";
      break;
  }

  return { statusClass, actions };
};

const btn_refresh = document.getElementById("btnSearchPatienData");
const btn_verordnung1 = document.getElementById("btnVerordnung1");
const btn_verordnung2 = document.getElementById("btnVerordnung2");
const btn_verordnung3 = document.getElementById("btnVerordnung3");
const btn_verordnung4 = document.getElementById("btnVerordnung4");
const btn_verordnung5 = document.getElementById("btnVerordnung5");
const btn_verordnung6 = document.getElementById("btnVerordnung6");
const clog = console.log;
const $$ = document.getElementById;
const btnRNr = document.getElementById("rechnungs_nr");


btn_refresh.addEventListener('click', async function (){
    let data = new FormData();
    data.append("rechnungs_nr", document.querySelector("#rechnungs_nr").value);
    data.append("vnr", document.querySelector("#vnr").value);
    //Validierung einfügen
    //Testparameter N04-9000501-21, B011178306, D490545437(debug)
    let response_patient = await fetch("dataport.php?mode=patient_data&rechnungsnr=" + data.get('rechnungs_nr') + "&versnr=" + data.get('vnr'));
    if (response_patient.ok) {
        let result = await response_patient.json();
        $("#tableHeader").find("tr:gt(0)").remove();
        create_row(result.header, "tableHeader", 1);
        visible_button(result);
    }
});

function create_row(output, outputTable, row_index) {
    let table = document.getElementById(outputTable);
    let row = table.insertRow(row_index);
    let cnt = 0;
    for (let prob in output) {
        window['cell' + cnt] = row.insertCell(cnt);
        window['cell' + cnt].className = "text-lg-center";
        window['cell' + cnt].innerHTML = output[prob];
        cnt++;
    }
}

function visible_button(result) {
    for (let n=0; n<result.patient_data.length; n++ ) {
        document.getElementById("btnVerordnung" + (n+1)).hidden = false;
    }
    clog(result);
    if (document.getElementById("btnVerordnung1").hidden === false) {
        btn_verordnung1.addEventListener('click', async function (){
            $("#tablePatient").find("tr:gt(0)").remove();
            $("#tableTaxen").find("tr:gt(0)").remove();
            create_row(result.patient_data[0], "tablePatient", 1);
            for (let n=0; n<result.taxen[0].length;n++) {
                create_row(result.taxen[0][n], "tableTaxen", n + 1);
            }
        });
    }
    if (document.getElementById("btnVerordnung2").hidden === false) {
        btn_verordnung2.addEventListener('click', async function (){
            $("#tablePatient").find("tr:gt(0)").remove();
            $("#tableTaxen").find("tr:gt(0)").remove();
            create_row(result.patient_data[1], "tablePatient", 1);
            for (let n=0; n<result.taxen[1].length;n++) {
                create_row(result.taxen[1][n], "tableTaxen", n + 1);
            }
        });
    }
    if (document.getElementById("btnVerordnung3").hidden === false) {
        btn_verordnung3.addEventListener('click', async function (){
            $("#tablePatient").find("tr:gt(0)").remove();
            $("#tableTaxen").find("tr:gt(0)").remove();
            create_row(result.patient_data[2], "tablePatient", 1);
            for (let n=0; n<result.taxen[2].length;n++) {
                create_row(result.taxen[2][n], "tableTaxen", n + 1);
            }
        });
    }
    if (document.getElementById("btnVerordnung4").hidden === false) {
        btn_verordnung4.addEventListener('click', async function (){
            $("#tablePatient").find("tr:gt(0)").remove();
            $("#tableTaxen").find("tr:gt(0)").remove();
            create_row(result.patient_data[3], "tablePatient", 1);
            for (let n=0; n<result.taxen[3].length;n++) {
                create_row(result.taxen[3][n], "tableTaxen", n + 1);
            }
        });
    }
}

function create_button(anzahl) {
    for (let n = 0; n < anzahl; n++) {

    }
    var newDiv = document.createElement("button");
    newDiv.className = "btn btn-primary";
    var newContent = document.createTextNode("Hi there and greetings!");
    newDiv.appendChild(newContent); // füge den Textknoten zum neu erstellten div hinzu.

    // füge das neu erstellte Element und seinen Inhalt ins DOM ein
    var currentDiv = document.getElementById("insertBtn");
    document.body.insertBefore(newDiv, currentDiv);
}
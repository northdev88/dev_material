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

document.getElementById("formPatientData").onsubmit = async function (event) {
    for (let n=0;n<6;n++) {
        document.getElementById("btnVerordnung" + (n+1)).hidden = true;
    }
    document.getElementById("rowSpinner").hidden = false;
    document.getElementById("rowVerordnung").hidden = true;
    document.getElementById("rowPatient").hidden = true;
    document.getElementById("rowTaxen").hidden = true;
    event.preventDefault();
    let data = new FormData();
    data.append("rechnungs_nr", document.querySelector("#rechnungs_nr").value);
    data.append("vnr", document.querySelector("#vnr").value);
    let response_patient = await fetch("dataport.php?mode=patient_data&rechnungsnr=" + data.get('rechnungs_nr') + "&versnr=" + data.get('vnr'));
    if (response_patient.ok) {
        let result = await response_patient.json();
        $("#tableHeader").find("tr:gt(0)").remove();
        create_row(result.header, "tableHeader", 1);
        if (result.patient_data.length === 0) {
            document.getElementById("rowSpinner").hidden = true;
            document.getElementById("rowVerordnung").hidden = false;
            document.getElementById("errorVerordnung").hidden = false;
            document.getElementById("errorVerordnung").innerHTML = "Für die Versichertennummer " + data.get('vnr') + " wurde in der Rechnung " + data.get('rechnungs_nr') +  " keine Verordnung gefunden";
        }
        else {
            document.getElementById("errorVerordnung").hidden = true;
            visible_button(result);
            document.getElementById("rowSpinner").hidden = true;
            document.getElementById("rowVerordnung").hidden = false;

        }
    }
    //response else implementieren
}

$('#rechnungs_nr').inputmask("A99\-9{6,7}\-99");

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

function visible_button(result) {
    for (let n=0; n<result.patient_data.length; n++ ) {
        document.getElementById("btnVerordnung" + (n+1)).hidden = false;
    }
    if (document.getElementById("btnVerordnung1").hidden === false) {
        document.getElementById("spanKunde1").innerHTML = 'Kunden-IK:  ' + result.patient_data[0].kunde_ik;
        btn_verordnung1.addEventListener('click', async function (){
            $("#tablePatient").find("tr:gt(0)").remove();
            $("#tableTaxen").find("tr:gt(0)").remove();
            document.getElementById("patientName").innerHTML = result.patient_data[0].nachname.toLowerCase() + ", " + result.patient_data[0].vorname.toLowerCase();
            document.getElementById("patientVers").innerHTML = result.patient_data[0].vers_nr;
            document.getElementById("patientStatus").innerHTML = result.patient_data[0].status;
            document.getElementById("patientGeb").innerHTML = result.patient_data[0].geburtsdatum;
            document.getElementById("patientStr").innerHTML = result.patient_data[0].strasse.toLowerCase();
            document.getElementById("patientOrt").innerHTML = result.patient_data[0].plz + "  " + result.patient_data[0].ort.toLowerCase();
            for (let n=0; n<result.taxen[0].length;n++) {
                create_row(result.taxen[0][n], "tableTaxen", n + 1);
            }
            document.getElementById("rowPatient").hidden = false;
            document.getElementById("rowTaxen").hidden = false;
        });
    }
    if (document.getElementById("btnVerordnung2").hidden === false) {
        document.getElementById("spanKunde2").innerHTML = 'Kunden-IK:  ' + result.patient_data[1].kunde_ik;
        btn_verordnung2.addEventListener('click', async function (){
            $("#tablePatient").find("tr:gt(0)").remove();
            $("#tableTaxen").find("tr:gt(0)").remove();
            document.getElementById("patientName").innerHTML = result.patient_data[1].nachname.toLowerCase() + ", " + result.patient_data[1].vorname.toLowerCase();
            document.getElementById("patientVers").innerHTML = result.patient_data[1].vers_nr;
            document.getElementById("patientStatus").innerHTML = result.patient_data[1].status;
            document.getElementById("patientGeb").innerHTML = result.patient_data[1].geburtsdatum;
            document.getElementById("patientStr").innerHTML = result.patient_data[1].strasse.toLowerCase();
            document.getElementById("patientOrt").innerHTML = result.patient_data[1].plz + "  " + result.patient_data[0].ort.toLowerCase();
            for (let n=0; n<result.taxen[1].length;n++) {
                create_row(result.taxen[1][n], "tableTaxen", n + 1);
            }
            document.getElementById("rowPatient").hidden = false;
            document.getElementById("rowTaxen").hidden = false;
        });
    }
    if (document.getElementById("btnVerordnung3").hidden === false) {
        document.getElementById("spanKunde3").innerHTML = 'Kunden-IK:  ' + result.patient_data[2].kunde_ik;
        btn_verordnung3.addEventListener('click', async function (){
            $("#tablePatient").find("tr:gt(0)").remove();
            $("#tableTaxen").find("tr:gt(0)").remove();
            document.getElementById("patientName").innerHTML = result.patient_data[2].nachname.toLowerCase() + ", " + result.patient_data[2].vorname.toLowerCase();
            document.getElementById("patientVers").innerHTML = result.patient_data[2].vers_nr;
            document.getElementById("patientStatus").innerHTML = result.patient_data[2].status;
            document.getElementById("patientGeb").innerHTML = result.patient_data[2].geburtsdatum;
            document.getElementById("patientStr").innerHTML = result.patient_data[2].strasse.toLowerCase();
            document.getElementById("patientOrt").innerHTML = result.patient_data[2].plz + "  " + result.patient_data[2].ort.toLowerCase();
            for (let n=0; n<result.taxen[2].length;n++) {
                create_row(result.taxen[2][n], "tableTaxen", n + 1);
            }
            document.getElementById("rowPatient").hidden = false;
            document.getElementById("rowTaxen").hidden = false;
        });
    }
    if (document.getElementById("btnVerordnung4").hidden === false) {
        document.getElementById("spanKunde4").innerHTML = 'Kunden-IK:  ' + result.patient_data[3].kunde_ik;
        btn_verordnung4.addEventListener('click', async function (){
            $("#tablePatient").find("tr:gt(0)").remove();
            $("#tableTaxen").find("tr:gt(0)").remove();
            document.getElementById("patientName").innerHTML = result.patient_data[3].nachname.toLowerCase() + ", " + result.patient_data[3].vorname.toLowerCase();
            document.getElementById("patientVers").innerHTML = result.patient_data[3].vers_nr;
            document.getElementById("patientStatus").innerHTML = result.patient_data[3].status;
            document.getElementById("patientGeb").innerHTML = result.patient_data[3].geburtsdatum;
            document.getElementById("patientStr").innerHTML = result.patient_data[3].strasse.toLowerCase();
            document.getElementById("patientOrt").innerHTML = result.patient_data[3].plz + "  " + result.patient_data[3].ort.toLowerCase();
            for (let n=0; n<result.taxen[3].length;n++) {
                create_row(result.taxen[3][n], "tableTaxen", n + 1);
            }
            document.getElementById("rowPatient").hidden = false;
            document.getElementById("rowTaxen").hidden = false;
        });
    }
}
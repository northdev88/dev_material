const btn_refresh = document.getElementById("btnAkt");
btn_refresh.addEventListener('click', async function (){
    let response = await fetch("dataport.php?param=kopfelemente");
    if (response.ok) {
        let result = await  response.json();
        console.log(result);
        fill_kopfelemente(result);
    }

    let response2 = await fetch("dataport.php?param=unter_rechnungen");
    if (response2.ok) {
        let result = await response2.json();
        console.log(result);
        document.getElementById("pDebug").innerText = result;
    }
})

const btn2_refresh = document.getElementById("btnAkt2");
btn2_refresh.addEventListener('click', async function (){
    let response = await fetch("dataport.php?param=kassen_kz");
    if (response.ok) {
        let result = await response.json();
        console.log(result);
        document.getElementById("pDebug").innerText = result;
    }
})

function fill_kopfelemente(result) {
    document.getElementById("tdIkAbsender").innerText = result.ik_absender;
    document.getElementById("tdIKEmpf").innerText = result.ik_empfaenger;
    document.getElementById("tdZeit").innerText = result.zeit_erstellung;
    document.getElementById("tdRef").innerText = result.datenaustauschreferenz;
    document.getElementById("tdLeistung").innerText = result.leistungsbereich;
    document.getElementById("tdAnw").innerText = result.anwendungsreferenz;
    document.getElementById("tdTest").innerText = result.testindikator;
    document.getElementById("tdAnzUNH").innerText = result.anzahl_unh;
}


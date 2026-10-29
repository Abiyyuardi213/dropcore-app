(() => {
    'use strict';
    if (!window.Chart || !window.productDashboardData) return;
    const data = window.productDashboardData;
    const statuses = document.getElementById('productStatusChart');
    if (statuses) new Chart(statuses, {
        type:'doughnut', data:{labels:['Tersedia','Menipis','Habis / minus','Berlebih'],datasets:[{data:data.statuses,backgroundColor:['#28a745','#ffc107','#dc3545','#007bff'],borderWidth:2}]},
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}}}
    });
    const movement = document.getElementById('productMovementChart');
    if (movement) new Chart(movement, {
        type:'bar', data:{labels:data.movements.labels,datasets:[{label:'Barang Masuk',data:data.movements.incoming,backgroundColor:'#28a745',borderRadius:3},{label:'Barang Keluar',data:data.movements.outgoing,backgroundColor:'#007bff',borderRadius:3}]},
        options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},scales:{y:{beginAtZero:true,ticks:{precision:0},title:{display:true,text:'Kuantitas tercatat'}},x:{grid:{display:false},ticks:{maxTicksLimit:12}}},plugins:{legend:{position:'bottom'}}}
    });
})();

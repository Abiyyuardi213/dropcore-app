(() => {
    'use strict';
    const map = document.getElementById('warehouseCapacityMap');
    if (!map) return;
    const warehouse = document.getElementById('warehouseMapSelect');
    const search = document.getElementById('warehouseMapSearch');
    const nodes = Array.from(map.querySelectorAll('.warehouse-map-node'));
    const originalOpen = new Map(Array.from(map.querySelectorAll('details')).map(area => [area, area.open]));
    function filter() {
        const term = search.value.trim().toLocaleLowerCase('id-ID');
        let matches = 0;
        nodes.forEach(node => {
            const selected = !warehouse.value || node.dataset.warehouseId === warehouse.value;
            let areaMatches = 0;
            node.querySelectorAll('.warehouse-area-node').forEach(area => {
                const areaMatch = !term || area.dataset.areaName.includes(term);
                let rackMatches = 0;
                area.querySelectorAll('.warehouse-rack-node').forEach(rack => {
                    rack.hidden = !areaMatch && !rack.dataset.rackName.includes(term);
                    if (!rack.hidden) rackMatches++;
                });
                area.hidden = !areaMatch && !rackMatches;
                if (!area.hidden) areaMatches++;
                area.open = term ? !area.hidden : originalOpen.get(area);
            });
            node.hidden = !selected || (!!term && !areaMatches);
            if (!node.hidden) matches++;
        });
        document.getElementById('warehouseMapEmpty').hidden = matches > 0 || nodes.length === 0;
    }
    warehouse.addEventListener('change', filter);
    search.addEventListener('input', filter);
})();

(function($){
    const formatNumber = function(number) {
        if (number >= 1000000000) {
            return (number / 1000000000).toFixed(0) + 'B';
        } else if (number >= 1000000) {
            return (number / 1000000).toFixed(0) + 'M';
        } else if (number >= 1000) {
            return (number / 1000).toFixed(0) + 'K';
        } else {
            return number.toString();
        }
    }

    const formatCurrency = function(number) {
        return number.toLocaleString('vi-VN', {
            style: 'currency',
            currency: 'VND'
        });
    }

    fetch('/admin/dashboard/revenue')
    .then(response => response.json())
    .then(json => {
        const { data } = json
        const total = data.currentMonth.total;
        const lastMonthTotal = data.lastMonth.total;
        const percentage = ((total - lastMonthTotal) / lastMonthTotal) * 100;
        if(percentage < 0) {
            $('#revenue-badge').removeClass('badge-light-success').addClass('badge-light-danger').find('.ki-duotone').removeClass('text-success').addClass('text-danger').removeClass('ki-arrow-up').addClass('ki-arrow-down');
        } else {
            $('#revenue-badge').removeClass('badge-light-danger').addClass('badge-light-success').find('.ki-duotone').removeClass('text-danger').addClass('text-success').removeClass('ki-arrow-down').addClass('ki-arrow-up');
        }
        // const percentageText = percentage > 0 ? `+${percentage.toFixed(2)}%` : `${percentage.toFixed(2)}%`;
        $('#total-revenue').text(`${formatNumber(total)}`);
        $('#revenue-percentage').text(`${percentage.toFixed(2)}%`);
        $('#total-revenue-normal').text(`${formatCurrency(data.currentMonth.normal)}`);
        $('#total-revenue-random').text(`${formatCurrency(data.currentMonth.random)}`);
        $('#total-revenue-minigame').text(`${formatCurrency(data.currentMonth.miniGame)}`);
    })
})(jQuery)

const addTableRow = $('.add-table-row');

addTableRow.on('click', function () {
    const tableClass = $(this).data('table');
    const bodyTable = $(`.${tableClass}`);
    const templateRow = bodyTable.find('tr:first').clone();
    const currentRow = bodyTable.find('tr').length;

    templateRow.find('input, select').each(function () {
        const name = $(this).attr('name');
        if (name) {
            const updatedName = name.replace(/\[\d+\]/, `[${currentRow}]`);
            $(this).attr('name', updatedName);
        }

        if ($(this).is('input')) {
            $(this).val('');
        } else if ($(this).is('select')) {
            $(this).prop('selectedIndex', 0);
        }
    });

    bodyTable.append(templateRow);
    updateRowIndexes(tableClass);
});

$(document).on('click', '.remove-table-row', function () {
    const tableClass = $(this).closest('table').attr('class');
    $(this).closest('tr').remove();

    updateRowIndexes(tableClass);
});

function updateRowIndexes(tableClass) {
    const bodyTable = $(`.${tableClass}`);
    bodyTable.find('tr').each(function (index) {
        $(this).find('input, select').each(function () {
            const name = $(this).attr('name');
            if (name) {
                const updatedName = name.replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', updatedName);
            }
        });
    });
}

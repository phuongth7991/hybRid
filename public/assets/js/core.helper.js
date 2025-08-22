window.DataTableHelper = {
    active: function (data, table, column, activeString = 'Hoạt động', deActiveString = 'Không hoạt động') {
        if (data[column] === 1 || data[column]) {
            return `<a href="javascript:void(0);" data-table="${table}" data-id="${data.id}" data-column="${column}" class="badge toggle-active badge-light-success fs-base">${activeString}</a>`
        } else {
            return `<a href="javascript:void(0);" data-table="${table}" data-id="${data.id}" data-column="${column}" class="badge toggle-active badge-light-danger fs-base">${deActiveString}</a>`
        }
    },
    isReward: function (data, table, column) {
        if (data[column] === 1 || data[column]) {
            return `<a href="javascript:void(0);" data-table="${table}" data-id="${data.id}" data-column="${column}" class="badge toggle-active badge-light-primary fs-base">Đã trao thưởng</a>`
        } else {
            return `<a href="javascript:void(0);" data-table="${table}" data-id="${data.id}" data-column="${column}" class="badge toggle-active badge-light-info fs-base">Chưa trao thưởng</a>`
        }
    },
    image: function (src) {
        if (src) {
            return `<img src="${cdnUrl + src}" width="40" height="auto" alt="">`
        }
        return '';
    },
    avatarRecord: function (row, column) {
        const baseUrl = cdnUrl.replace(/\/storage\/?$/, "").replace(/\/$/, "");
        const defaultAvatar = `${baseUrl}/assets/media/svg/avatars/blank.svg`;
        const src = row[column]
            ? `${cdnUrl}${row[column].replace(/^storage\//, "")}`
            : defaultAvatar;

        return `<img src="${src}" width="40" height="40" style="border-radius:5px;object-fit:cover;" alt="user">`;
    },

    formatDate(row, column) {
        return moment(row[column]).format('DD-MM-Y HH:mm:ss');
    },
    checkIcon(row, column, _class) {
        if (row[column] === 1) {
            return `<i class="fa fa-check text-danger"></i>`
        }
        return '-'
    },
    formatMoney(row, column) {
        const keys = column.split('.');
        let value = row;
        for (const key of keys) {
            value = value[key];
            if (value === undefined) {
                return 0;
            }
        }
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            currencyDisplay: 'symbol',
            maximumFractionDigits: 3,
        }).format(value);
    },
    badge(row, column, _class) {
        if (Array.isArray(row[column])) {
            return row[column]
                .map(item => `<span class="badge badge-${_class}">${item.name}</span>`)
                .join(' ');
        }

        return `<span class="badge badge-${_class}">${row[column]}</span>`
    },
    html(row, column) {
        const parse = new DOMParser();
        return parse.parseFromString(row[column], "text/html").body.innerText;
    },
    formatStatus(row, column, statusConstant) {
        const listStatus = JSON.parse(statusConstant);
        return `<span class="badge badge-${listStatus[row[column]]['class']}">${listStatus[row[column]]['text']}</span>`
    },
}

function ImgUpload() {
    var imgWrap = "";
    var imgArray = [];
    $('.upload__inputfile').each(function () {
        $(this).on('change', function (e) {
            imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
            var maxLength = $(this).attr('data-max_length');

            var files = e.target.files;
            var filesArr = Array.prototype.slice.call(files);
            var iterator = 0;
            filesArr.forEach(function (f, index) {

                if (!f.type.match('image.*')) {
                    return;
                }

                if (imgArray.length > maxLength) {
                    return false
                } else {
                    var len = 0;
                    for (var i = 0; i < imgArray.length; i++) {
                        if (imgArray[i] !== undefined) {
                            len++;
                        }
                    }
                    if (len > maxLength) {
                        return false;
                    } else {
                        imgArray.push(f);

                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                            imgWrap.append(html);
                            iterator++;
                        }
                        reader.readAsDataURL(f);
                    }
                }
            });
        });
    });

    $('body').on('click', ".upload__img-close", function (e) {
        var file = $(this).parent().data("file");
        for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i].name === file) {
                imgArray.splice(i, 1);
                break;
            }
        }
        var p = $(this).attr('data-path');
        var currentList = $(this).parents('.input-gallery-wrap').find('.gallery-remove-list').val();
        if (currentList) {
            currentList = JSON.parse(currentList);
        } else {
            currentList = [];
        }
        if (p) {
            currentList.push(p);
            // currentValue.splice(currentValue.indexOf(p), 1)
            // $(this).parents('.input-gallery-wrap').find('.gallery-current-value').val(JSON.stringify(currentValue));
        }
        $(this).parents('.input-gallery-wrap').find('.gallery-remove-list').val(JSON.stringify(currentList));
        $(this).parent().parent().remove();
    });
}

$(document).ready(function () {
    $(document).on('click', '.toggle-active', function () {
        const table = $(this).data('table');
        const id = $(this).data('id');
        const column = $(this).data('column');
        axios.get(`/admin/util?table=${table}&id=${id}&column=${column}`).then(() => {
            window.LaravelDataTables.kt_datatable_horizontal_scroll.ajax.reload();
        })
    })
    ImgUpload();
});

$('.date-range-picker').flatpickr({
    mode: "range",
    altInput: false,
    altFormat: "d-m-Y",
    dateFormat: "d-m-Y",
});

$('.date-picker').flatpickr({
    altInput: false,
    altFormat: "d-m-Y",
    dateFormat: "d-m-Y",
});


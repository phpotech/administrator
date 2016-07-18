<script>
$(function()
{
    $('[data-filter-type="date"]').datepicker({
        format: 'yyyy-mm-dd',
        clearBtn: false,
        multidate: false
    });

    $('[data-filter-type="daterange"]').daterangepicker({
        format: 'YYYY-MM-DD',
        clearBtn: true,
        multidate: true
    });

    $('input[data-filter-type="number_range"]').slider({
        //
    });

    // activate language switcher
    $('button[data-locale]').click(function() {
        var fn = $(this), locale = fn.data('locale');
        var translatable = fn.closest('.translatable-block').find('.translatable');

        translatable.map(function(index, item) {
            var fn = $(item);
            if (fn.data('locale') == locale) {
                fn.removeClass('hidden');
            } else {
                fn.addClass('hidden');
            }
        });
    });

    $(document).ready(function(){
        $('input[type=checkbox]', 'input[type=radio]').iCheck({
            checkboxClass: 'icheckbox_minimal-purple',
            radioClass: 'iradio_minimal-purple',
            increaseArea: '20%' // optional
        });
    });
});
</script>
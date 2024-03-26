/*
Template Name: Dason - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Datatables Js File
*/
var currentLanguage = localStorage.getItem('Dason-language')==null ? "ar": localStorage.getItem('Dason-language');

console.log("language = "+currentLanguage);
    function initDataTable() {


        var dataTableOptions = {
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'colvis']
        };
    
        // Conditionally include the language URL only if the language is Arabic
        if (currentLanguage === 'ar') {
            dataTableOptions.language = {
                url: `https://cdn.datatables.net/plug-ins/1.11.5/i18n/${currentLanguage}.json`
            };
        }


        $('#datatable-buttons').DataTable(dataTableOptions);
    }

    $(document).ready(function() {
        initDataTable();

         // Listen for changes in localStorage
         window.addEventListener('storage', function(e) {
            console.log("localstorage change triggered");
            if (e.key === 'Dason-language') {
                currentLanguage = e.newValue || 'en';
                $('#datatable-buttons').DataTable().destroy(); // Destroy existing DataTable
                initDataTable(); // Re-initialize DataTable with the new language
            }
        });

        $(".dataTables_length select").addClass('form-select form-select-sm');
    });
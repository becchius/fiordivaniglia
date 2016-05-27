/**
 * Created with JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 30/08/13
 * Time: 17.00
 * To change this template use File | Settings | File Templates.
 */

function toggleIndirizzoAziendale(indirizzoAziendale,prefix){
    if(indirizzoAziendale){
        $(prefix+'partita_iva').addClassName('required-entry');
        $(prefix+'company').addClassName('required-entry');
        $('partita_iva_container').show();
        $('company_container').show();
        toggleCodiceFiscale(true,prefix);


    }else{
        $(prefix+'partita_iva').removeClassName('required-entry');
        $(prefix+'partita_iva').value = '';
        $(prefix +'company').removeClassName('required-entry');
        $(prefix+'company').value = '';
        $('partita_iva_container').hide();
        $('company_container').hide();
        toggleCodiceFiscale(false,prefix);
    }
}

function toggleCodiceFiscale(richiestaFattura,prefix){
    if($(prefix+'indirizzo_aziendale').checked){
        richiestaFattura = true;
    }
    if(richiestaFattura){
        $(prefix+'vat_id').addClassName('required-entry');
        $$('#vat_id_container label em').each(function(element){
            element.show();
        });
        $$('#vat_id_container label span.required').each(function(element){
            element.show();
        });
        $$('#vat_id_container label').each(function(element){
            element.addClassName('required');
        });


    }else{
        $(prefix+'vat_id').removeClassName('required-entry');
        $$('#vat_id_container label em').each(function(element){
            element.hide();
        });
        $$('#vat_id_container label span.required').each(function(element){
            element.hide();
        });
        $$('#vat_id_container label').each(function(element){
            element.removeClassName('required');
        });
    }
}



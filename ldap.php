<?php

function ldapIPQuery($dn, $filter) {

    $ldaprdn  = 'NEWTELCOSRV\jcleese';     // ldap rdn oder dn
    $ldappass = 'N3wt3lco';  // entsprechendes password
    $ds=ldap_connect("ldap://ldap.newtelco.dev:389");  // must be a valid LDAP server!
    $r=ldap_bind($ds, $ldaprdn, $ldappass);     // this is an "anonymous" bind, typically
    $sr=ldap_search($ds, $dn, $filter);  
    $info = ldap_get_entries($ds, $sr);
    $attrs = ldap_get_attributes($ds,$info);

    $ip_list = array();

    for ($i=0; $i<$info["count"]; $i++) {
        $hostname = $info[$i]["dnshostname"][0];
        $userIPAddress = gethostbyname($hostname);
        $ip_list[] = $userIPAddress;
    }

    return json_encode($ip_list);
    ldap_close($ds);
}

if(isset($_GET['technik_computers'])){
    $technikDN = "OU=Technik,OU=Users,OU=Frankfurt,dc=NEWTELCO,dc=LOCAL";
    $technikFilter = "(&(cn=*)(objectCategory=computer))";

    echo ldapIPQuery($technikDN,$technikFilter);

} else if(isset($_GET['all_computers'])) {
    $allDN = "OU=Users,OU=Frankfurt,dc=NEWTELCO,dc=LOCAL";
    $allFilter = "(&(cn=*)(objectCategory=computer))";

    echo ldapIPQuery($allDN,$allFilter);

}

?>

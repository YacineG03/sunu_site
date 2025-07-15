
package com.soapclient.services;

import javax.xml.bind.annotation.XmlAccessType;
import javax.xml.bind.annotation.XmlAccessorType;
import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;
import javax.xml.bind.annotation.XmlType;


/**
 * <p>Classe Java pour anonymous complex type.
 * 
 * <p>Le fragment de schéma suivant indique le contenu attendu figurant dans cette classe.
 * 
 * <pre>
 * &lt;complexType&gt;
 *   &lt;complexContent&gt;
 *     &lt;restriction base="{http://www.w3.org/2001/XMLSchema}anyType"&gt;
 *       &lt;sequence&gt;
 *         &lt;element name="jeton" type="{http://www.w3.org/2001/XMLSchema}string"/&gt;
 *       &lt;/sequence&gt;
 *     &lt;/restriction&gt;
 *   &lt;/complexContent&gt;
 * &lt;/complexType&gt;
 * </pre>
 * 
 * 
 */
@XmlAccessorType(XmlAccessType.FIELD)
@XmlType(name = "", propOrder = {
    "jeton"
})
@XmlRootElement(name = "listUsersRequest")
public class ListUsersRequest {

    @XmlElement(required = true)
    protected String jeton;

    /**
     * Obtient la valeur de la propriété jeton.
     * 
     * @return
     *     possible object is
     *     {@link String }
     *     
     */
    public String getJeton() {
        return jeton;
    }

    /**
     * Définit la valeur de la propriété jeton.
     * 
     * @param value
     *     allowed object is
     *     {@link String }
     *     
     */
    public void setJeton(String value) {
        this.jeton = value;
    }

}

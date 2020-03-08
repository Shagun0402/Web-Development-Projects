/* CSE 5335: Web Data Management and XML
 * Project 7: XSLT program to write math.xsl to query reed.xml data to XHTML using xslt.java
 * Name: Shagun Paul
 * UTA ID: 1001557958
 */
//package project7;

import javax.xml.parsers.*;
import org.w3c.dom.*;
import javax.xml.transform.*;
import javax.xml.transform.dom.*;
import javax.xml.transform.stream.*;
import java.io.*;

public class xslt 
{
	public static void main ( String args[] ) throws Exception 
	{
		File stylesheet = new File("math.xsl");
		File xmlfile  = new File("reed.xml");
		DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		DocumentBuilder db = dbf.newDocumentBuilder();
		Document document = db.parse(xmlfile);
		StreamSource stylesource = new StreamSource(stylesheet);
		TransformerFactory tf = TransformerFactory.newInstance();
		Transformer transformer = tf.newTransformer(stylesource);
		DOMSource source = new DOMSource(document);
		StreamResult result = new StreamResult("math.html");
		transformer.transform(source,result);
	 }
}

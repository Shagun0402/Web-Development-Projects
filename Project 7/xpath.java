/* CSE 5335: Web Data Management and XML
 * Project 7: Xpath.java to query reed.xml data.
 * Name: Shagun Paul
 * UTA ID: 1001557958
 */
//package project7;

import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathConstants;
import javax.xml.xpath.XPathFactory;

import org.w3c.dom.NamedNodeMap;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.w3c.dom.Text;
import org.xml.sax.InputSource;

public class xpath 
{
	public static void main(String args[]) throws Exception
	{
		System.out.println("Printing all titles of MATH course at are being taught in LIB 204: \n");
		evaluate("/root/course[subj='MATH' and place/building='LIB' and place/room='204']/title", "reed.xml");
		System.out.println("Printing name of faculty that teaches MATH 412: \n");
		evaluate("/root/course[subj='MATH' and crse='412']/instructor","reed.xml");
		System.out.println("Printing all the courses  being taught by Wieting: \n");
		evaluate("/root/course[instructor='Wieting']/title", "reed.xml");
	}
	
	static void print (Node n)
	{
		if (n instanceof Text)
		    System.out.print(((Text) n).getData());
		else {
			    NodeList c = n.getChildNodes();
			    NamedNodeMap attributes = n.getAttributes();
			    for (int i = 0; i < attributes.getLength(); i++)
				System.out.print(attributes.item(i).getNodeValue());
			    for (int k = 0; k < c.getLength(); k++)
				print(c.item(k));
			    System.out.print("\n");
			}
	}
		
	static void evaluate(String query, String document) throws Exception
	{
		XPathFactory xpathFactory = XPathFactory.newInstance();
		XPath xpath = xpathFactory.newXPath();
		InputSource inputSource = new InputSource(document);
		NodeList result = (NodeList) xpath.evaluate(query,inputSource,XPathConstants.NODESET);
		for (int i = 0; i < result.getLength(); i++)
		    print(result.item(i));
		System.out.println();
	}
}

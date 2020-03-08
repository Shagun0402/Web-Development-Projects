/* CSE 5335: Web Data Management and XML
 * WDM Project 7: DOM.java using JAva API to query reed.xml data.
 * Name:Shagun Paul
 * UTA ID: 1001557958
 */
//package project7;
import java.io.File;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.w3c.dom.Text;

public class dom 
{
	public static void main(String args[]) throws Exception 
	{
		DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		DocumentBuilder db = dbf.newDocumentBuilder();
		Document doc = db.parse(new File("reed.xml"));
		Node root = doc.getDocumentElement();
		System.out.println("\t This Program prints all the MATH courses that are being taught in LIB 204 \n");
		query1(root);
		
	}

	static void print(Node e)
	{
		if (e instanceof Text)
		    System.out.print(((Text) e).getData());
		else 
		{
		    NodeList c = e.getChildNodes();
		    System.out.print("<"+e.getNodeName()+">");
		    for (int k = 0; k < c.getLength(); k++)
			print(c.item(k));
		    System.out.print("</"+e.getNodeName()+">");
		}
	}
	
	static void query1(Node n)
	{
		NodeList nl1 = ((Element) n).getElementsByTagName("course");
		for (int i=0; i< nl1.getLength(); i++)
		{
			Node n1 = nl1.item(i);
			if (n1.getNodeType()== n1.ELEMENT_NODE)
			{
				Element e = (Element) n1;
				String subject = e.getElementsByTagName("subj").item(0).getChildNodes().item(0).getNodeValue();
				if(subject.equals("MATH"))
				{
					String building = e.getElementsByTagName("building").item(0).getChildNodes().item(0).getNodeValue();
					if(building.equals("LIB"))
					{
						String room = e.getElementsByTagName("room").item(0).getChildNodes().item(0).getNodeValue();
						if(room.equals("204"))
						{
							System.out.println(e.getElementsByTagName("title").item(0).getTextContent());
						}
					}
				}
				
			}
		}
	}
}
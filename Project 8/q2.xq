<courses>
{
	for $k in doc("reed.xml")//course/title
	let $x := doc("reed.xml")//course[title=$k]/instructor
	return <course>{
						$k,
						<instructors>{
										$x
									 }</instructors>
				   }</course>

}</courses>
<instructors>
{
	for $j in doc("reed.xml")//course/instructor
	let $x := doc("reed.xml")//course[instructor=$j]/title
	return <instructor>
	{
		$j,
		$x
	}</instructor>
}
</instructors>
<instructors>
{
	let $k := doc("reed.xml")//course
	for $instr in distinct-values($k/instructor)
	let $flag := count($k[instructor=$instr])
	return <instructor>
	{
		<name> {$instr} </name>,
		<number-of-courses> {$flag} </number-of-courses>
	}</instructor>
}
</instructors>
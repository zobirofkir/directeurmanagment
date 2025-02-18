import { Link } from "@inertiajs/react"

const Home = () => {
  return (
    <div className="flex flex-col gap-4 justify-center items-center h-screen">
        <h1 className="text-black font-bold text-center text-3xl">Welcome Home</h1>

        <Link href="/login" className="text black font-bold text-4xl bg-black text-white px-4 py-2 rounded-md">Login</Link>
    </div>
  )
}

export default Home

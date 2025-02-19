import { Link } from '@inertiajs/react';

const HeaderComponent = () => {

  return (
    <header className="bg-white shadow-sm w-full">
      <div className="container mx-auto px-4 py-3 flex justify-between items-center">
        {/* Logo */}
        <div className="text-xl font-bold text-gray-800">
          <Link href="/" className="hover:text-gray-600 transition-colors">
            Directeur System
          </Link>
        </div>


        {/* Auth Buttons */}
        <div
          className={` lg:flex lg:items-center`}
        >
          <Link
            href='/login'
            className="block py-2 lg:py-0 transition-colors font-bold text-md text-white bg-black py-1 px-3 rounded-full"
          >
            Login
          </Link>
        </div>
      </div>
    </header>
  );
};

export default HeaderComponent;

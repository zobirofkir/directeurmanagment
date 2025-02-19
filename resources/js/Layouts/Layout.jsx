import FooterComponent from '@/components/FooterComponent'
import HeaderComponent from '@/components/HeaderComponent'
import React from 'react'

const Layout = ({children}) => {
  return (
    <>
        <HeaderComponent />
        <section>
            {children}
        </section>
        <FooterComponent />
    </>
  )
}

export default Layout

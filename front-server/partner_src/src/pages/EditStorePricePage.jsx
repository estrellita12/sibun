import React from "react";

import styled from "styled-components";

import StorePrice from "../components/Addstore/StorePrice";
import NavigationHeader from "../components/Header/NavigationHeader";

const EditStorePricePage = () => {
  const storeId = localStorage.getItem("storeId");

  const onChange = () => {};

  return (
    <EditPrice>
      <NavigationHeader title="이용가격" value="back" />
      <div className="container">
        <StorePrice getData={onChange} value="edit" storeId={storeId} />
      </div>
    </EditPrice>
  );
};
const EditPrice = styled.div`
  padding-top: 80px;
  height: 100vh;
  & .container {
    padding: 20px 15px;
    height: 100%;
  }

  & .dragcontainer {
    padding-top: 20px;
  }
`;

export default EditStorePricePage;

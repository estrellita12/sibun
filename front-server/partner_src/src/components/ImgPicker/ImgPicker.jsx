import React, { useEffect, useRef, useState } from "react";
import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";
import styled from "styled-components";
import { Img, Title } from "../../styles/styledComponent";
import IconClose from "../../assets/image/close_img.png";
import apis from "../../api/apis";

const ImgPicker = ({ img, setImg, storeId }) => {
  function handleOnDragEnd(result) {
    if (result.destination !== null) {
      const currentItem = [...img];
      const draggingItemIndex = result.source.index;
      const afterDragItemIndex = result.destination.index;
      const removeItem = currentItem.splice(draggingItemIndex, 1);
      currentItem.splice(afterDragItemIndex, 0, removeItem[0]);
      setImg(currentItem);
    }
  }

  const onClickDelete = (e) => {
    console.log(e);
    if (!e.store_img_idx) {
      const filterData = img.filter((el) => el.id !== e.id);
      setImg(filterData);
    } else {
      apis.deleteStoreImg(storeId, e.store_img_idx).then((res) => {
        if (res.data.result === "000") {
          console.log("삭제되었습니다.");
          const filterData = img.filter(
            (el) => el.store_img_idx !== e.store_img_idx
          );
          setImg(filterData);
        }
      });
    }
  };

  return (
    <Picker>
      <DragDropContext onDragEnd={handleOnDragEnd}>
        <Droppable droppableId="cardlist" direction="horizontal">
          {(provided) => (
            <div
              className="cardlists"
              {...provided.droppableProps}
              ref={provided.innerRef}
            >
              <DragContent>
                {img?.map((e, i) => (
                  <Draggable
                    draggableId={String(e.id || e.store_img_idx)}
                    index={i}
                    key={String(e.id || e.store_img_idx)}
                  >
                    {(provided, snapshot) => {
                      return (
                        <div
                          {...provided.draggableProps}
                          {...provided.dragHandleProps}
                          ref={provided.innerRef}
                          className="item"
                        >
                          <button
                            className="deletedbtn"
                            onClick={() => onClickDelete(e)}
                          >
                            <Img src={IconClose} width={17} />
                          </button>

                          <Img
                            src={e.url || e.store_img_data}
                            width={100}
                            right={15}
                            fit="cover"
                            radius={5}
                          />

                          {i === 0 ? (
                            <div className="main">
                              <Title color="#fff" size={14}>
                                대표사진
                              </Title>
                            </div>
                          ) : (
                            ""
                          )}
                        </div>
                      );
                    }}
                  </Draggable>
                ))}
              </DragContent>
              {provided.placeholder}
            </div>
          )}
        </Droppable>
      </DragDropContext>
    </Picker>
  );
};

const Picker = styled.div`
  width: 100%;
  overflow-x: scroll;
  & .cardlists {
    height: 100px;
  }
`;

const DragContent = styled.div`
  display: flex;

  margin-left: 10px;
  & .item {
    position: relative;
  }

  & .deletedbtn {
    position: absolute;
    top: 6px;
    right: 20px;
  }

  & .main {
    width: 100px;
    z-index: 200;
    position: absolute;
    bottom: 0px;

    background-color: rgba(51, 51, 51, 0.85);
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;

    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2px 0px;
  }
`;

export default ImgPicker;
